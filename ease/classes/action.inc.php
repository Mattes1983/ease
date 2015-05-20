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

    class _Action extends _GlobalFunctions {
	
	public function fctGetSetAction()
	{

	    // get all $_GET- & $_POST-Params
	    $arrParams = array_merge( $_GET, $_POST );
	    if( count( $arrParams ) )
	    {
		// Action-Switch
		switch( $arrParams['action'] )
		{
		    // Insert new extension on link
		    case "intern":
			if( $arrParams['ext'] )
			{
			    _Link::fctSaveIntern( $arrParams );
			}
			break;
			
		    // Switch Level
		    case "level":
			if( $arrParams['lvl'] ) $_SESSION['easevars']['level'] = $arrParams['lvl'];
			break;
			
		    // Move Link
		    case "move":
			if( $arrParams['move_id'] && $arrParams['lin_parent'] )
			{
			    _Link::fctMoveLink( $arrParams );
			}
			break;
			
		    // Delete Link
		    case "delete":
			if( $arrParams['delete_id'] )
			{
			    _Link::fctDeleteLink( $arrParams['delete_id'] );
			}
			break;
			
		    // Copy Link
		    case "copy":
			_Link::fctCopyLink( $arrParams );
			break;
		    
		    // Join Link
		    case "join":
			_Link::fctJoinLink( $arrParams );
			break;

		    // Content-Save (Ajax)
		    case "save_content_request":
			if( $arrParams['lin_id'] )
			{
			    $_C = new _Content();
			    $_C->fctSaveContent( $arrParams['lin_id'],$arrParams['name'],$arrParams['value'] );
			}
			exit;
			break;
			
		    // Content-GET (Ajax)
		    case "get_content_request":
			if( $arrParams['lin_id'] )
			{
			    $_C = new _Content();
			    echo json_encode( $_C->fctGetContentArray( $arrParams['lin_id'],$arrParams['name'] ) );
			}
			exit;
			break;

		    // Get Link-Content in an Array (Ajax)
		    case "get_link_request":
			if( $arrParams['lin_id'] )
			{
			    $_PD = new _ParseDoc();
			    $_PD->boolNoEditmode = true;
			    $_PD->boolReturnContentArray = true;
			    $arrContent = $_PD->fctRenderLinks( 0,$arrParams['lin_id'] );
			    echo json_encode( $arrContent );
			}
			exit;
			break;
			
		    // Search Document-GET (Ajax)
		    case "search_document_request":
			if( $arrParams['search'] )
			{
			    $arrDocuments = _Search::fctDocumentSearch( $arrParams['search'] );
			    if( count( $arrDocuments ) )
			    {
				echo "<ul>";
				foreach( $arrDocuments as $arrD )
				{
				    $arrURL = _Documents::fctGetDocURL( $arrD['doc_id'] );
				    if( !$arrD['doc_title'] ) $arrD['doc_title'] = $arrD['doc_name'];
				    echo '<li><a href="javascript:;" onclick="'. $arrURL['js'] .'">'. $arrD['doc_title'] .'</a></li>';
				}
				echo "</ul>";
			    }
			}
			exit;
			break;
			
		    // EASE-Function (Ajax)
		    case "ease_function":
			if( $arrParams['ease_class'] && $arrParams['ease_function'] )
			{
			    $_Class = new $arrParams['ease_class'];
			    echo json_encode( $_Class->$arrParams['ease_function']( $arrParams['ease_param_1'],$arrParams['ease_param_2'],$arrParams['ease_param_3'],$arrParams['ease_param_4'],$arrParams['ease_param_5'],$arrParams['ease_param_6'],$arrParams['ease_param_7'],$arrParams['ease_param_8'],$arrParams['ease_param_9'],$arrParams['ease_param_10'] ) );
			}
			exit;
			break;
			
		    // Extension-Item-Title-GET (Ajax)
		    case "get_extension_item_title_request":
			if( $arrParams['lin_id'] || $arrParams['ext_id'] )
			{
			    $_LINK = new _Link;
			    echo $_LINK->fctGetLinkExtensionTitle( $arrParams['lin_id'], $arrParams['ext_id'], $arrParams['ext_name'], $arrParams['ext_value'] );
			}
			exit;
			break;

		    // Extension-Function (Ajax)
		    case "extension_function":
			if( $arrParams['extension_class'] && $arrParams['extension_function'] )
			{
			    $this->fctQuery( "SELECT * FROM ". $this->Config['database']['table_prefix'] ."extension WHERE ext_name = '". $arrParams['extension_class'] ."' AND ext_active = '1'" );
			    if( count( $this->arrSQL ) == 1 )
			    {
				$strClassName = _Extensions::fctExtensionClass( $arrParams['extension_class'] );
				$_Extension = new $strClassName;
				$_Extension->ExtensionVars['ext_id'] = $this->arrSQL[0]['ext_id'];
				$_Extension->fctSetLang();
				$_Extension->$arrParams['extension_function']( $arrParams['para_values'] );
			    }
			}
			exit;
			break;
		}
	    }
	}
	
    }

?>
