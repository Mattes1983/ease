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

    // Ease-Includes
    include_once(dirname(__FILE__)."/globals/includes.inc.php");
    
    $_GF = new _GlobalFunctions();
    $_GF->fctCreateExternBasicFunctions();
    
    // Get actual Document
    $_D = new _Documents;
    $_D->fctSetDoc();
    
    // Render Toolbox
    $_PD = new _ParseDoc;
    $_PD->fctRenderTools();
    
?>