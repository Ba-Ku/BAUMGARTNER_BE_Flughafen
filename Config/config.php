<?php

error_reporting(E_ALL);

include "Services/InputValidation.php";
include "Controller/FlightController.php";
include "Services/Database.php";
include "Model/FlightListModel.php";
include "Model/PassengerListModel.php";
include "View/JsonView.php";

define("DATABASE_TYPE", "mysql");
define("DATABASE_HOST", "localhost");
define("DATABASE_NAME", "fh_beb_flights");
define("DATABASE_CHARSET", "utf8");
define("DATABASE_USER", "root");
define("DATABASE_PASSWORD", "");

define("RESTFUL_URLVECTOR_FOR_FLIGHT","http://localhost/BAUMGARTNER_BEB_Flughafen_V2.0/index.php?action=get-passengers&flightId=");
define("RESTFUL_URLVECTOR_FOR_FLIGHTSTABLE", "http://localhost/BAUMGARTNER_BEB_Flughafen_V2.0/index.php?action=get-flights");