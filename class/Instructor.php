<?php

require_once "Database.php";

/**
 * Class Instructor
 * 
 * Handles instructor-related operations such as:
 * - Fetching paginated instructor list
 * - Counting total instructors
 * - Fetching instructor dropdown data
 */

class Instructor
{

    /**
     * @var mysqli Database connection instance
     */
    private $conn;

    /**
     * Instructor constructor.
     * Initializes database connection.
     */
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /**
     * Get paginated list of instructors
     *
     * @param int $page Current page number
     * @param int $limit Number of records per page
     * 
     * @return array Returns status and instructor data OR error message
     */
    public function totalInstructors($page, $limit)
    {
        try {
            $offset = ($page - 1) * $limit;
            $sql = "SELECT id, name, email, phone, role, isActive FROM users WHERE role = 'Instructor' ORDER BY id LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ii', $limit, $offset);
            $stmt->execute();

            $result = $stmt->get_result();
            $data = [];

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            return ["status" => true, "data" => $data];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Count total number of instructors
     *
     * @return array Returns status and total count OR error message
     */
    public function countInstructor()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'Instructor'";
            $result = $this->conn->query($sql);
            return ["status" => true, "count" => $result->fetch_assoc()['total']];
        } catch (Exception $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Get all instructors (for dropdown/select use)
     *
     * @return array List of instructors with id and name
     */
    public function getInstructors()
    {
        $sql = "SELECT id, name FROM users WHERE role = 'Instructor'";
        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get paginated list of instructors (server-side processing for DataTables)
     *
     * Supports search, filtering, and sorting for DataTables server-side rendering.
     * Returns paginated instructor records with search capability.
     *
     * @param int $start Starting index for pagination
     * @param int $length Number of records to return
     * @param string $search Search term to filter instructors by name, email, or phone
     * @param string $orderColumn Column name to order by
     * @param string $orderDirection Sort direction (ASC or DESC)
     * @param int $draw Draw counter from DataTables request
     *
     * @return array{draw: int, recordsTotal: int, recordsFiltered: int, data: array, error?: string}
     */
    public function getInstructorsServerSide($start, $length, $search, $orderColumn, $orderDirection, $draw)
    {
        try {

            $baseQuery = "
            FROM users
            WHERE role = 'Instructor'
        ";

            // SEARCH

            if (!empty($search)) {

                $baseQuery .= "
                AND (
                    name LIKE ? OR
                    email LIKE ? OR
                    phone LIKE ?
                )
            ";
            }

            // TOTAL RECORDS

            $totalQuery = "
            SELECT COUNT(*) as total
            FROM users
            WHERE role = 'Instructor'
        ";

            $totalResult = $this->conn->query($totalQuery);

            $totalRecords = $totalResult->fetch_assoc()['total'];

            // FILTERED RECORDS

            $filteredQuery = "
            SELECT COUNT(*) as total
            {$baseQuery}
        ";

            $stmt = $this->conn->prepare($filteredQuery);

            if (!empty($search)) {

                $searchTerm = "%{$search}%";

                $stmt->bind_param(
                    "sss",
                    $searchTerm,
                    $searchTerm,
                    $searchTerm
                );
            }

            $stmt->execute();

            $filteredResult = $stmt->get_result();

            $filteredRecords = $filteredResult->fetch_assoc()['total'];

            // MAIN DATA QUERY

            $dataQuery = "
            SELECT id, name, email, phone, role, isActive
            {$baseQuery}
            ORDER BY {$orderColumn} {$orderDirection}
            LIMIT ? OFFSET ?
        ";

            $stmt = $this->conn->prepare($dataQuery);
            if (!empty($search)) {
                $searchTerm = "%{$search}%";
                $stmt->bind_param(
                    "sssii",
                    $searchTerm,
                    $searchTerm,
                    $searchTerm,
                    $length,
                    $start
                );
            } else {
                $stmt->bind_param(
                    "ii",
                    $length,
                    $start
                );
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return [
                "draw" => intval($draw),
                "recordsTotal" => intval($totalRecords),
                "recordsFiltered" => intval($filteredRecords),
                "data" => $data
            ];
        } catch (Exception $e) {

            return [
                "draw" => intval($draw),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $e->getMessage()
            ];
        }
    }
}
