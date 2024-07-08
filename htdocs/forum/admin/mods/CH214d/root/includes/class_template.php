<?php
/***************************************************************************
 *							class_template.php
 *							------------------
 *	begin		: 29/08/2004
 *	copyright	: Ptirhiik
 *	email		: ptirhiik@clanmckeen.com
 *
 *	Version		: 0.0.7 - 18/05/2006
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
 
if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

/***************************************************************************
*
* Note : the compile part is in its greatest part a simplified version of phpBB 2.1.x 
* template engine, so credits for good things go to PsoTFX, and eventual bugs to me :).
*
 ***************************************************************************/

include($phpbb_root_path . 'includes/template.' . $phpEx);

class template_class extends Template
{
	var $custom_root;
	var $no_debug;

	// Various counters and storage arrays
	var $block_names;
	var $block_else_level;
	var $block_nesting_level;

	function template_class($root='.', $custom_root='')
	{
		$this->custom_root = $custom_root;
		parent::Template($root);

		// init global vars
		$this->block_names = array();
		$this->block_else_level = array();
		$this->block_nesting_level = 0;
		$this->no_debug = false;
	}

	// ensure extreme style v2 compliancy adding the xs_include switch
	function make_filename($filename, $xs_switch=false)
	{
		if ( (substr($filename, 0, 1) != '/') && !empty($this->custom_root) && !defined('IN_ADMIN') )
		{
       		$w_filename = phpbb_realpath($this->root . '/' . $this->custom_root . '/' . $filename);
			if ( file_exists($w_filename) && !is_dir($w_filename) && !is_link($w_filename) )
			{
				$filename = $this->custom_root . '/' . $filename;
			}
		}
		return parent::make_filename($filename, $xs_switch);
	}

	// ensure extreme style v2 compliancy
	function subtemplates_make_filename($filename)
	{
		return $this->make_filename($filename);
	}

	function set_switch($switch_name, $value=true)
	{
		$this->assign_block_vars($switch_name . ($value ? '' : '_ELSE'), array());
	}

	// recall from memory the result of a parsing without sending it to browser
	function save(&$save)
	{
		$save = $this->_tpldata;
	}

	function destroy()
	{
		if ( isset($this->vars) )
		{
			$this->_tpldata = array('.' => array(0 => array()));
			$this->vars = &$this->_tpldata['.'][0];
		}
		else
		{
			parent::destroy();
		}
	}

	function restore(&$save)
	{
		$this->_tpldata = $save;
		if ( isset($this->vars) )
		{
			$this->vars = &$this->_tpldata['.'][0];
		}
	}

	function pparse($handle)
	{
		if ( defined('DEBUG_TEMPLATE') && !$this->no_debug )
		{
			echo '<!-- Start of : ' . $this->files[$handle] . ' :: ' . $handle . ' -->' . "\n";
		}
		$res = parent::pparse($handle);
		if ( defined('DEBUG_TEMPLATE') && !$this->no_debug )
		{
			echo '<!-- End of : ' . $this->files[$handle] . ' :: ' . $handle . ' -->' . "\n";
		}

		return $res;
	}

	function get_pparse($handle)
	{
		ob_start();
		$this->pparse($handle);
		$res = ob_get_contents();
		ob_end_clean();
		return $res;
	}

	// Include a seperate template
	function _tpl_include($filename)
	{
		if ( !empty($filename) )
		{
			$this->set_filenames(array($filename => $filename));
			$this->pparse($filename);
		}
	}

	// insert a tpl (xs already covers the include functionality, so let's it do the job if present)
	function compile($code, $do_not_echo = false, $retvar = '')
	{
		if ( defined('XS_TAG_INCLUDE') )
		{
			$template_php = parent::compile($code, $do_not_echo, $retvar);
			return $template_php;
		}

		// escape code
		$code = str_replace('\'', '\\\'', $code);

		// clean php scripts & tabs
		$match_php_tags = array('#\<\?php .*?\?\>#is', '#\<\script language="php"\>.*?\<\/script\>#is', '#\<\?.*?\?\>#s', '#\<%.*?%\>#s');
		$code = preg_replace($match_php_tags, '', $code);
		$code = preg_replace("/([\n\r])([ \t]*)/", '\1', $code);

		// split in block
		preg_match_all('#<!-- (.*?) (.*?)?[ ]?-->#s', $code, $blocks);
		$text_blocks = preg_split('#<!-- (.*?) (.*?)?[ ]?-->#s', $code);
		for ( $i = 0; $i < count($text_blocks); $i++ )
		{
			$this->compile_var_tags($text_blocks[$i]);
		}

		// analyse tags
		$compile_blocks = array();
		$count_text_blocks = count($text_blocks);
		for ( $i = 0; $i < $count_text_blocks; $i++ )
		{
			switch ( $blocks[1][$i] )
			{
				case 'BEGIN':
					$this->block_else_level[] = false;
					$compile_blocks[] = $this->compile_tag_block($blocks[2][$i]);
					break;

				case 'BEGINELSE':
					$this->block_else_level[ sizeof($this->block_else_level) - 1 ] = true;
					$compile_blocks[] = '}} else {';
					break;

				case 'END':
					array_pop($this->block_names);
					$compile_blocks[] = ((array_pop($this->block_else_level)) ? '}' : '}}') . "\n";
					break;

				case 'INCLUDE':
					$compile_blocks[] = '$this->_tpl_include(\'' . trim($blocks[2][$i]) . '\');' . "\n";
					break;

				default:
					$text_block = $this->compile_var_tags($blocks[0][$i]);
					$text_blocks[$i] .= (trim($text_block) == '') ? '' : "\n" . $text_block;
					$compile_blocks[] = '';
					break;
			}
		}

		// build result
		$template_php = '';
		$count_text_blocks = count($text_blocks);
		for ( $i = 0; $i < $count_text_blocks; $i++ )
		{
			// remove orphean lines when appropriate
			$text_block = preg_replace("/^([\n\r]{2,})/", '', $text_blocks[$i]);
			$template_php .= (trim($text_block) == '' ? '' : ($do_not_echo ? '$' . $retvar . ' .= ' : 'echo ') . '\'' . $text_block . '\';' . "\n") . trim($compile_blocks[$i]);
		}

		// return result
		return str_replace(' ?><?php ', '', $template_php);
	}

	function compile_var_tags(&$text_blocks)
	{
		// change template varrefs into PHP varrefs
		$varrefs = array();

		// This one will handle varrefs WITH namespaces
		preg_match_all('#\{(([a-z0-9\-_]+?\.)+?)([a-z0-9\-_]+?)\}#is', $text_blocks, $varrefs);

		$count_varrefs_1 = count($varrefs[1]);
		for ($j = 0; $j < $count_varrefs_1; $j++)
		{
			$namespace = $varrefs[1][$j];
			$varname = $varrefs[3][$j];
			$new = '\' . ' . $this->generate_block_varref($namespace, $varname) . ' . \'';

			$text_blocks = str_replace($varrefs[0][$j], $new, $text_blocks);
		}

		// This will handle the remaining root-level varrefs
		$text_blocks = preg_replace('#\{([a-z0-9\-_]*?)\}#is', "' . \$this->_tpldata['.'][0]['\\1'] . '", $text_blocks);

		return $text_blocks;
	}

	function compile_tag_block($tag_args)
	{
		// Allow for control of looping (indexes start from zero):
		// foo(2)    : Will start the loop on the 3rd entry
		// foo(-2)   : Will start the loop two entries from the end
		// foo(3,4)  : Will start the loop on the fourth entry and end it on the fourth
		// foo(3,-4) : Will start the loop on the fourth entry and end it four from last
		if (preg_match('#^(.*?)\(([\-0-9]+)(,([\-0-9]+))?\)$#', $tag_args, $match))
		{
			$tag_args = $match[1];
			$loop_start = ($match[2] < 0) ? '$_' . $tag_args . '_count ' . ($match[2] - 1) : $match[2];
			$loop_end = ($match[4]) ? (($match[4] < 0) ? '$_' . $tag_args . '_count ' . $match[4] : ($match[4] + 1)) : '$_' . $tag_args . '_count';
		}
		else
		{
			$loop_start = 0;
			$loop_end = '$_' . $tag_args . '_count';
		}

		$tag_template_php = '';
		array_push($this->block_names, $tag_args);

		if (sizeof($this->block_names) < 2)
		{
			// Block is not nested.
			$tag_template_php = '$_' . $tag_args . "_count = (isset(\$this->_tpldata['$tag_args.'])) ?  sizeof(\$this->_tpldata['$tag_args.']) : 0;";
		}
		else
		{
			// This block is nested.

			// Generate a namespace string for this block.
			$namespace = implode('.', $this->block_names);

			// Get a reference to the data array for this block that depends on the
			// current indices of all parent blocks.
			$varref = $this->generate_block_data_ref($namespace, false);

			// Create the for loop code to iterate over this block.
			$tag_template_php = '$_' . $tag_args . '_count = (isset(' . $varref . ')) ? sizeof(' . $varref . ') : 0;';
		}

		$tag_template_php .= "\n" . 'if ($_' . $tag_args . '_count) {';
		$tag_template_php .= "\n" . 'for ($_' . $tag_args . '_i = ' . $loop_start . '; $_' . $tag_args . '_i < ' . $loop_end . '; $_' . $tag_args . '_i++){' . "\n";

		return $tag_template_php;
	}

	function generate_block_varref($namespace, $varname)
	{
		if ( defined('XS_TAG_INCLUDE') )
		{
			return parent::generate_block_varref($namespace, $varname);
		}

		// Strip the trailing period.
		$namespace = substr($namespace, 0, strlen($namespace) - 1);

		// Get a reference to the data block for this namespace.
		$varref = $this->generate_block_data_ref($namespace, true);

		// Prepend the necessary code to stick this in an echo line.
		// Append the variable reference.
		$varref .= '[\'' . $varname . '\']';

		return $varref;
	}
}

?>