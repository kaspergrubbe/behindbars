<?php
	/**
	 * This class collects and displays the rsting in realtime WITHOUT refreshing the page
	 * 
	 * @author Rochak Chauhan
	 */
	
	class AjaxRatingCounter {
		
		var $numberOfStart = 10;
		var $code = '';
		
		/**
		 * Contructor function
		 *
		 * @return object
		 */
		function AjaxRatingCounter($displayHeading = true) {
			if ($displayHeading === true) {
				//$this->code = '<center>	<h1>Ajax Rating Counter</h1></center>';
			}
		}		
		
		/**
		 * Fucntion to display stars
		 *
		 * @param int $rating
		 * @param string $idName
		 * 
		 * @return string
		 */
		function addStars($rating, $idName) {
			
			if (trim($rating) == '') {
				die("ERROR: Rating  cannot be left blank");
			}
			
			$rating = ceil($rating);
			
			$this->code .= '<script type="text/javascript" language="JavaScript">
						displayStars('.$rating.', "'.$idName.'");
					</script>';
			
		}	
		
		/**
		 * function to display stars
		 */
		function displayStars() {
			
			return $this->code;
		}
	}
?>