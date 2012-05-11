<?php

class UIPager extends CBasePager
{
	#Number of pages to start with 	<<first and last>>
	public $MaxButtonLimit = 4;
	public $PaginatorElementView = 'as.views.UI.paginatorelement';

	public $Output = true;

	protected function getPageRange($pageCount, $currentPage)
	{
		#calculate the number on the first button
		$beginPage = max(0, $currentPage-(int)($this->MaxButtonLimit/2));
		
		#calculate the last page number
		$endPage = $beginPage + $this->MaxButtonLimit - 1;
		
		if($endPage >= $pageCount)
		{
			$endPage = $pageCount-1;
			$beginPage = max(0,$endPage-$this->MaxButtonLimit+1);
		}
		
		return array($beginPage, $endPage);
	}
		
	protected function getButtons()
	{
		$currentPage = $this->getCurrentPage();
		$pageCount = $this->getPageCount();
		
		#no buttons needed
		if($pageCount < 2)
			return array();
		else
		{
			$buttons = array();
			
			list($firstPage, $lastPage) = $this->getPageRange($pageCount, $currentPage);
			
			#first page
			if($pageCount >= $this->MaxButtonLimit)
				$buttons[] = array('first', $this->createPageUrl(0));
			
			#no previous page
			if($currentPage <= 0)
				$currentPage = 0;
			
			#hide when pretty useless
			elseif($pageCount > 2)
				$buttons[] = array('previous', $this->createPageUrl($currentPage - 1));

			#page numbers
			for($i = $firstPage; $i <= $lastPage; ++$i)
				$buttons[] = array($i, $this->createPageUrl($i), ($i == $currentPage? true: false));

			#no last page
			if($currentPage + 1 >= $pageCount)
				$currentPage = $pageCount;

			#hide when pretty useless				
			elseif($pageCount > 2)
				$buttons[] = array('next', $this->createPageUrl($currentPage + 1));
			
			#first page
			if($pageCount >= $this->MaxButtonLimit)
				$buttons[] = array('last', $this->createPageUrl($pageCount));
			
			return $buttons;
		}
		
	}
	
	protected function render_pager($buttons = array())
	{
		return $this->render($this->PaginatorElementView, array('buttons' => $buttons), true);
	}

	public function init()
	{
	
	}
	
	public function run()
	{
		$buttons = $this->getButtons();
		$output = $this->render_pager($buttons);
		
		if($this->Output)
			echo $output;
		
		return $output;
	}
}
