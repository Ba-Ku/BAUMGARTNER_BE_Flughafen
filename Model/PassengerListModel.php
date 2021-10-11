<?php


class PassengerListModel
{
    private $flightId;
    private $resultTableFromQuery;

    public function __construct($inputFlightId)
    {
        $this->flightId = $inputFlightId;
        $this->resultTableFromQuery = array();
    }

    public function queryDatabaseForPassengerList()
    {
        $linkToDatabase = new Database();
        $pdo = $linkToDatabase->getDatabaseContent();
        $flight = array();
        $flight['flightName'] = "";
        $sqlQuery = "SELECT p.firstname, p.lastname, f.flightname FROM passengers p JOIN flights f ON p.flights_id = f.id WHERE p.flights_id = {$this->flightId}";
        try {
            $flight['url'] = RESTFUL_URLVECTOR_FOR_FLIGHTSTABLE;
            $preparedStatement = $pdo->prepare($sqlQuery);
            $preparedStatement->execute();
            $fetchedArray = $preparedStatement->fetchAll();
            return $fetchedArray;
        } catch (PDOException $PDOException) {
            error_log("ERROR: " . $PDOException->getMessage());
        }
    }

}