<?php
/*
    Pager class which facilitates your work with view part on MVC architecture.

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

   @author     Bartosz KONIECZNY  (http://www.bart-konieczny.com/)
   @copyright  2011 Bartosz KONIECZNY
   @license    http://www.gnu.org/licenses  GNU Lesser General Public License (LGPL)
   @version    1.0
*/

class Pager {

  /**
   * Private variable with pager options.
   * @var array
   */
  private $options = array();

  /**
   * Class constructor. It initializes the options.
   * @access public
   * @param array $options Options list.
   * @return void
   */
  public function __construct($options = array())  
  {  
	$this->options = $options;
  }
  
  /**
   * Prepares the Pager for a view template. 
   * @access public
   * @return array List of parameters which may be used in the view part.
   */
  public function setPages()
  {
    return array('last' => $this->setLastPage(),
	'after' => $this->getAfter(),
	'before' => $this->getBefore(),
	'actual' => $this->options['page'],
	'next' => $this->setNext(),
	'previous' => $this->setPrevious()
	);
  }
  
  /**
   * Gets number of pages before the actual page.
   * @access private
   * @return array List of pages.
   */
  private function getBefore()
  {
    if($this->options['page'] > 1) 
	{
      $limit = $this->options['page']-$this->options['before']-1;
      $page = $this->options['page']-1; 
      while($page > 0 && $page > $limit) 
	  { 
        $result[$page] = $page; 
        $page--;
      }
    return array_reverse($result);
    }
    else 
	{
      return array();
    }
  }
  
  /**
   * Gets number of pages after the actual page.
   * @access public
   * @return array List of pages.
   */
  private function getAfter()
  {  
    if($this->options['page'] < $this->last) 
	{
      $limit = $this->options['page'] + $this->options['after'] + 1;
      $page = $this->options['page']+1;    
      while(($page < $limit && ($page <= $this->last))) 
	  { 
        $result[$page] = $page; 
        $page++;
      } 
      return $result;
    }
    else 
	{
      return array();
   }
  }
  
  /**
   * Sets the next page number. May be used for the anchors like "next", "next page" etc.
   * @access private
   * @return int Returns integer when the next page exists.
   */
  private function setNext()
  {
    if($this->options['page'] < $this->last-1)
	{
	  return $this->options['page'] + 1;
	}
  }

  /**
   * Sets the previous page number. May be used for the anchors like "previous", "previous page" etc.
   * @access private
   * @return int Returns integer when the previous page exists.
   */

  private function setPrevious()
  {
    if($this->options['page'] > 1)
	{
	  return $this->options['page'] - 1;
	}
  }
  
  /**
   * Sets the last page number. May be used for the anchors like "end", "the last page" etc.
   * @access private
   * @return int Returns integer with the laste page number.
   */

  private function setLastPage()
  {
    $this->last = ceil($this->options['all']/$this->options['perPage']);
    if($this->last == 0) 
	{
      $this->last = 1;
    }
    return $this->last;
  }
    

}