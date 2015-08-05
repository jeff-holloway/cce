<?php

// Author: de77.com
// Licence: MIT
// Homepage: http://de77.com/php/extract-title-author-and-number-of-pages-from-pdf-with-php
// Version: 21.07.2010


class PDFInfo
{
	public $author;
	public $title;
	public $pages;
	public $string;
	
	public function load($filename)
	{
		$this->string = file_get_contents($filename);
	}
	public function title() {
		return $this->get_attributes("dc:title");
	}
	public function producer() {
		return $this->get_attributes("pdf:Producer");
	}
	public function creator() {
		return $this->get_attributes("dc:creator");
	}
	public function get_attributes($attribute) {
		
		$start = strpos($this->string, "<".$attribute.">") + (strlen($attribute) + 2);
		$length = strpos(substr($this->string, $start), '</'.$attribute.'>');

		$rval = 'Untitled';
		if ($length) 
		{
			$rval = strip_tags(substr($this->string, $start, $length));
			$rval = $this->pdfDecTxt($rval);
		}
		
		return $rval;
	}
	public function pages() {

		$pages = 0;
		if (preg_match("/\/N\s+([0-9]+)/", $this->string, $found))
		{
			$pages = $found[1]; 
		}
		else
		{
			$pos = strpos($this->string, '/Type /Pages ');
			if ($pos !== false)
			{
				$pos2 = strpos($this->string, '>>', $pos);
				$string = substr($this->string, $pos, $pos2 - $pos);
				$pos = strpos($this->string, '/Count ');
				$pages = (int) substr($this->string, $pos+7);
			}
		}
		
		return $pages;
	}
		
	private function pdfDecTxt($txt)
	{
		$len = strlen($txt);
		$out = '';
		$i = 0;
		while ($i<$len)
		{
			if ($txt[$i] == '\\')
			{
				$out .= chr(octdec(substr($txt, $i+1, 3)));
				$i += 4;			
			}
			else
			{
				$out .= $txt[$i];
				$i++;
			}
		}
		
		if ($out[0] == chr(254))
		{
			$enc = 'UTF-16';
		}
		else
		{
			$enc = mb_detect_encoding($out);
		}

		return iconv($enc, 'UTF-8', $out);
	}
}