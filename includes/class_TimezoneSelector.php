<?php

// *********************************************************************
// By Dave Hale - moodtracker.com
// Version: 1.2
//
// DESCRIPTION:
// The TimezoneSelector dynamically creates a <select></select> control.
// It lets you include the current date and time in the given timezone by
// specifying a 1 in the show() method. The date and time format can be 
// indicated in the optional string parameter which follows the standard
// php date/time format string.
//
// The select list is sorted by timezone or by the current timezone's time
// if the date/time is requested.
//
//
// USAGE:
// 
//   $tzs = new TimezoneSelector(string $ControlName [, string $DefaultTimeZone [, string $ControlAttributes [, string $ClientTimeZoneJS ]]]);
//
//   $tzs->show([integer $OnOff [, string $DateFormat]]);
// 
//
// PARAMETERS:
//
//   ---- __construct() method ----
//
//   $ControlName - string (required)
//      This is the name of the name attribute in the select control (i.e. <select name="thisIsWhatItIs">)
// 
//   $DefaultTimeZone - string (optional)
//      This is the time zone you want the control to be set to.
//
//   $ControlAttributes - string (optional)
//      This is a string of name value pairs for controlling additional attributes in the select control (i.e. class="mycss" onchange="myfunction()")
//
//   $ClientTimeZoneJS - string (optional)
//      This is a string that represents the current time offset in minutes from GMT given in JavaScript's Date.getTimezoneOffset() method.  This is useful for giving the TimezoneSelector a "best guess" initial default time zone setting.
//
//
//   ----- show() method -----
//
//   $OnOff - integer (optional)
//      This is an integer that turns on/off the display of each time zone's date/time.
//
//   $DateFormat - string (optional)
//      This is the format for displaying the date/time of each time zone.
//
// ====================================================================
//
//
// USAGE EXAMPLES:
// 
// Minimum:
// --------
//   $tzs = new TimezoneSelector("mySelectControlName");
//   $tzs->show();
//
// Set defaults and control attributes
// -----------------------------------
//   $tzs = new TimezoneSelector("mySelectControlName","America/Denver","class=\"mycss\" onchange=\"document.getElementById('frm').submit();\"");
//   $tzs->show(1);
//
// Set defaults, control attributes, and client timezone guess
// -----------------------------------------------------------
//   $tzs = new TimezoneSelector("mySelectControlName","America/Denver","class=\"mycss\"", "420");
//   $tzs->show(1);
//
//   Note: The $clientTimeZoneJS optional parameter.  This comes from JavaScript's Date.getTimezoneOffset() method.
//
// Show Date (default format):
// ---------------------------
//   $tzs = new TimezoneSelector("mySelectControlName","America/Denver","class=\"mycss\" onchange=\"document.getElementById('frm').submit();\"");
//   $tzs->show(1);
//
//
// Show Date (custom format):
// --------------------------
//   $tzs = new TimezoneSelector("mySelectControlName");
//   $tzs->show(1,"m-d-Y H:i:s (O)");
//
// ********************************************************************

class TimezoneSelector {
  private $name;
  private $clientOffset; 
  private $extra;
  private $baseDateTime;
  private $controlTz;
  private $error;

  public function __construct($name, $controlTz = "", $extra = "", $offset = "") {
    $this->error = "";
    if (!$name) {
      $this->error = "The required 'name' parameter was not given.";
    }
    $this->name = $name;
    if (preg_match("/^-?[0-9]+$/", $offset)) {
      $this->clientOffset = $offset * -60; // convert javascript minutes to seconds and invert
    }
    else {
      $this->clientOffset = "";
    }
    $this->extra = $extra;
    $this->controlTz = $controlTz;

    $utc = new DateTimeZone("UTC");
    $this->baseDateTime = new DateTime("now", $utc);
  }

  public function show($showDateTime = 0, $dateFormat = "") {
    if ($this->error) {
      print $this->error;
      return;
    }

    printf('<select %s %s>', 
      $this->name ? "name=\"" . $this->name . "\"" : "",
      $this->extra);

    $timezone_identifiers = DateTimeZone::listIdentifiers();

    if ($showDateTime) {
      foreach($timezone_identifiers as $val) {
        $atz = new DateTimeZone($val);
        $aDate = new DateTime("now", $atz);
		$offsetcheck = $atz->getOffset($aDate);
		if ($tmparr[$offsetcheck] == 'done') continue;
		$tmparr[$offsetcheck] = 'done';
        if ($showDateTime) {
          $timeArray["$val"] = $aDate->format("U") + $atz->getOffset($aDate) . "|" . $val;
        }
        else {
          $timeArray["$val"] = $val;
        }
      }

      asort($timeArray);

      if (!$dateFormat) $dateFormat = "j M Y (g:ia)";
      $matchfound = 0;
      foreach($timeArray as $key => $val) {
        $nTz = new DateTimeZone($key);
        $nDate = new DateTime("now", $nTz);
        if (preg_match("/^-?[0-9]+$/",$this->clientOffset) && $this->controlTz == "" && !$matchfound) {
          $nOffset = $nDate->getOffset();
          printf('<option %s value="%s">%s</option>
            ', $nOffset == $this->clientOffset ? "selected=\"selected\"" : "", $key, $nDate->format($dateFormat) . " - " . str_replace("_", " ", $key));
          if ($nOffset == $this->clientOffset) $matchfound = 1;
        }
        else {
          printf('<option %s value="%s">%s</option>
            ', $key == $this->controlTz ? "selected=\"selected\"" : "", $key, $nDate->format($dateFormat) . " - " . str_replace("_", " ", $key));
        }
      }
    }
    else {
      foreach($timezone_identifiers as $val) {
        printf('<option %s value="%s">%s</option>', $val == $this->controlTz ? "selected=\"selected\"" : "", $val, str_replace("_", " ", $val));
      }
    }

    print "</select>";
  }
}

?>
