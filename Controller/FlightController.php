<?php


class FlightController extends InputValidation
{
    private $jsonView;
    private $maximumNumberOfFlights;

    public function __construct()
    {
        $this->jsonView = new JsonView();
        $this->maximumNumberOfFlights = 0;
    }

    public function route()
    {
        if (isset($_GET['action'])) {
            $actionToRoute = $_GET['action'];
            $streamlinedActionToRoute = $this->streamlineString($actionToRoute);
            $sanitizedActionToRoute = $this->sanitizeString($streamlinedActionToRoute);
            $this->selectList($sanitizedActionToRoute);
        }
    }

    private function getFlightId()
    {
        if (isset($_GET['flightId'])) {
            $flightID = $_GET['flightId'];
            $streamlindeFlightId = $this->streamlineString($flightID);
            $sanitizedFlightId = $this->sanitizeString($streamlindeFlightId);
            return $sanitizedFlightId;
        }
    }

    private function checkIfFlightIdIsSet()
    {
        if (!isset($_GET['flightId'])) {
            $this->displayError(2);
            return false;
        }
    }

    private function checkIfFlightIdIsInbound()
    {
        $this->queryPdoForFlightList();
        if ($_GET['flightId'] > $this->maximumNumberOfFlights || $_GET['flightId'] < 0) {
            $this->displayError(3);
            return false;
        }
    }

    private function selectList($actionFromUrl)
    {
        switch ($actionFromUrl) {
            case "GET-FLIGHTS":
                $this->displayFlightsList();
                break;
            case "GET-PASSENGERS":
                $this->checkIfFlightIdIsSet();
                $this->checkIfFlightIdIsInbound();
                $chosenFlightId = $this->getFlightId();
                $this->displayPassengerList($chosenFlightId);
                break;
            default:
                $this->displayError(1);
                break;
        }
    }

    private function queryPdoForFlightList()
    {
        $resultTableFromQuery = array();
        $flightsList = new FlightListModel();
        $flightsListTable = $flightsList->queryDatabaseForFlightslist();
        $flightCounter = 0;
        foreach ($flightsListTable as $flight) {
            $flightDataAsArray = array();
            $flightDataAsArray['flightId'] = $flight['id'];
            $flightDataAsArray['name'] = $flight['flightname'];
            $flightDataAsArray['url'] = RESTFUL_URLVECTOR_FOR_FLIGHT . $flight['id'];
            $resultTableFromQuery['flights'][] = $flightDataAsArray;
            $flightCounter++;
        }
        $this->maximumNumberOfFlights = $flightCounter;
        $sortById = array_column($resultTableFromQuery['flights'], 'flightId');
        array_multisort($sortById, SORT_DESC, $resultTableFromQuery['flights']);
        return $resultTableFromQuery;
    }

    private function displayFlightsList()
    {
        $flightsListTable = $this->queryPdoForFlightList();
        $this->jsonView->streamOutput($flightsListTable);
    }

    private function queryPdoForPassengerList($inputFlightId)
    {
        $passengerListPdo = new PassengerListModel($inputFlightId);
        $fetchedArrayFromPdo = $passengerListPdo->queryDatabaseForPassengerList();
        $flight = array();
        $flight['flightName'] = "";
        foreach ($fetchedArrayFromPdo as $passengerlist) {
            $passenger = array();
            $passenger['lastname'] = $passengerlist['lastname'];
            $passenger['firstname'] = $passengerlist['firstname'];
            $flight['passengers'][] = $passenger;
        }
        $flight['flightName'] = $passengerlist['flightname'];
        $sortByLastname = array_column($flight['passengers'], 'lastname');
        array_multisort($sortByLastname, SORT_ASC, $flight['passengers']);
        $flight['url'] = RESTFUL_URLVECTOR_FOR_FLIGHTSTABLE;
        return $flight;
    }

    private function displayPassengerList($inputFlightId)
    {
        $passengerListTable = $this->queryPdoForPassengerList($inputFlightId);
        $this->jsonView->streamOutput($passengerListTable);
    }

    private function displayError($errorNumber)
    {
        switch ($errorNumber) {
            case 1:
                $noListChosen = "No list has been chosen!";
                $this->jsonView->streamOutput($noListChosen);
                break;
            case 2:
                $noFlightIdChosen = "No flight id has been chosen!";
                $this->jsonView->streamOutput($noFlightIdChosen);
                break;
            case 3:
                $flightIdOutOfBound = "The chosen flight-id exceeds the id threshold";
                $this->jsonView->streamOutput($flightIdOutOfBound);
                break;
        }
    }
}