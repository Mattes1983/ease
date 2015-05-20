<?php

/*******************************************************************

    Copyright notice

    (c) 2012 Matthias Dahms <matthias.dahms@ease-cms.com>

    This file is part of ease CMS.

    ease CMS is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    ease CMS is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with ease CMS.  If not, see <http://www.gnu.org/licenses/>.

    This copyright notice MUST APPEAR in all copies of the script!

*******************************************************************/

    class _Language extends _GlobalFunctions {
	
	public function __construct()
	{
	    parent::__construct(); 
	    
	    // English
	    $this->fctSetLang(array(
		"easeCMS"=>"ease CMS",
		"Toolbar"=>"Toolbar",
		"EaseVersion"=>"V. ".$this->EaseVars['ease_version'],
		"EditMode"=>"Edit-Mode",
		"MoveMode"=>"Move-Mode",
		"JoinMode"=>"Join-Mode",
		"CopyMode"=>"Copy-Mode",
		"DeleteMode"=>"Delete-Mode",
		"LinkInfoMode"=>"LinkInfoMode-Mode",
		"Back"=>"Back",
		"Next"=>"Next",
		"New"=>"New",
		"Start"=>"Start",
		"Reload"=>"Reload",
		"Logout"=>"Logout",
		"Search"=>"Search",
		"Normal"=>"Normal",
		"Maximize"=>"Maximize",
		"Minimize"=>"Minimize",
		"Close"=>"Close",
		"Layout"=>"Layout",
		"Elements"=>"Elements",
		"Document"=>"Page",
		"CMS-Settings"=>"CMS-Settings",
		"LoginIncorrect"=>"Your login is incorrect. Please try again.",
		"Password"=>"Password",
		"BrowserUpdate1"=>"Browser-Update",
		"BrowserUpdate2"=>"You need a browser of the latest generation to work with the ease CMS. Please start or install one of these browsers in the latest version.",
		"Loading"=>"loading..."
	    ),"en");
	    
	    // Deutsch
	    $this->fctSetLang(array(
		"easeCMS"=>"ease CMS",
		"Toolbar"=>"Toolbar",
		"EaseVersion"=>"V. ".$this->EaseVars['ease_version'],
		"EditMode"=>"Bearbeiten-Modus",
		"MoveMode"=>"Verschieben-Modus",
		"JoinMode"=>"Verkn&uuml;pfen-Modus",
		"CopyMode"=>"Kopieren-Modus",
		"DeleteMode"=>"L&ouml;schen-Modus",
		"LinkInfoMode"=>"Info-Modus",
		"Back"=>"Zur&uuml;ck zur letzten Seite",
		"Next"=>"Weiter zur nächsten Seite",
		"New"=>"Eine neue Seite anlegen",
		"Start"=>"Zur Startseite wechseln",
		"Reload"=>"Seiten aktualisieren",
		"Logout"=>"Logout",
		"Search"=>"Suchen",
		"Normal"=>"Normal",
		"Maximize"=>"Maximieren",
		"Minimize"=>"Minimieren",
		"Close"=>"Schlie&szlig;en",
		"Layout"=>"Layout",
		"Elements"=>"Inhalte",
		"Document"=>"Seite",
		"CMS-Settings"=>"Einstellungen",
		"Password"=>"Passwort",
		"LoginIncorrect"=>"Die Login-Daten sind falsch. Bitte versuchen Sie es noch einmal.",
		"BrowserUpdate1"=>"Browser-Update",
		"BrowserUpdate2"=>"Sie ben&ouml;tigen einen Browser der neusten Generation, um mit dem CMS zu arbeiten. Bitte starten oder installieren Sie einen dieser Browser in der aktuellsten Version.",
		"Loading"=>"wird geladen..."
	    ),"de");
	    
	}
	
    }

?>
