<?php

/**
 * Youversion
 * 
 * This file must be placed in the
 * system/plugins/ folder in your ExpressionEngine installation.
 *
 * @package Youversion
 * @version 1.0.0
 * @author Erik Reagan http://erikreagan.com
 * @author Dan Frist original author of WP plugin
 * @copyright Copyright (c) 2010 Erik Reagan
 * @see http://github.com/erikreagan/youversion
 */

$plugin_info       = array(
   'pi_name'        => 'YouVersion',
   'pi_version'     => '1.0.0',
   'pi_author'      => 'Dan Frist, Erik Reagan',
   'pi_author_url'  => 'http://youversion.com',
   'pi_description' => 'Automatically link scripture references to YouVersion',
   'pi_usage'       => Youversion::usage()
   );
   

class Youversion
{

   var $return_data  = "";

   function Youversion()
   {
      global $REGX, $TMPL;
      
      // list of books and their abbreviations  (OSIS)
      $osis = array(
         'Genesis'         => 'Gen',
         'Exodus'          => 'Exod',
         'Leviticus'       => 'Lev',
         'Numbers'         => 'Num',
         'Deuteronomy'     => 'Deut',
         'Joshua'          => 'Josh',
         'Judges'          => 'Judg',
         'Ruth'            => 'Ruth',
         '1 Samuel'        => '1Sam',
         '2 Samuel'        => '2Sam',
         '1 Kings'         => '1Kgs',
         '2 Kings'         => '2Kgs',
         '1 Chronicles'    => '1Chr',
         '2 Chronicles'    => '2Chr',
         'Ezra'            => 'Ezra',
         'Nehemiah'        => 'Neh',
         'Esther'          => 'Esth',
         'Job'             => 'Job',
         'Psalms'          => 'Ps',
         'Proverbs'        => 'Prov',
         'Ecclesiastes'    => 'Eccl',
         'Song of Solomon' => 'Song',
         'Isaiah'          => 'Isa',
         'Jeremiah'        => 'Jer',
         'Lamentations'    => 'Lam',
         'Ezekiel'         =>'Ezek',
         'Daniel'          => 'Dan',
         'Hosea'           => 'Hos',
         'Joel'            => 'Joel',
         'Amos'            => 'Amos',
         'Obadiah'         => 'Obad',
         'Jonah'           => 'Jonah',
         'Micah'           => 'Mic',
         'Nahum'           => 'Nah',
         'Habakkuk'        => 'Hab',
         'Zephaniah'       => 'Zeph',
         'Haggai'          => 'Hag',
         'Zechariah'       => 'Zech',
         'Malachi'         => 'Mal',
         'Matthew'         => 'Matt',
         'Mark'            => 'Mark',
         'Luke'            => 'Luke',
         'John'            => 'John',
         'Acts'            => 'Acts',
         'Romans'          => 'Rom',
         '1 Corinthians'   => '1Cor',
         '2 Corinthians'   => '2Cor',
         'Galatians'       => 'Gal',
         'Ephesians'       => 'Eph',
         'Philippians'     => 'Phil',
         'Colossians'      => 'Col',
         '1 Thessalonians' =>'1Thess',
         '2 Thessalonians' => '2Thess',
         '1 Timothy'       => '1Tim',
         '2 Timothy'       => '2Tim',
         'Titus'           => 'Titus',
         'Philemon'        => 'Phlm',
         'Hebrews'         => 'Heb',
         'James'           => 'Jas',
         '1 Peter'         => '1Pet',
         '2 Peter'         => '2Pet',
         '1 John'          => '1John',
         '2 John'          => '2John',
         '3 John'          => '3John',
         'Jude'            => 'Jude',
         'Revelation'      => 'Rev'
      );

      $data = ($TMPL->tagdata !== '') ? $REGX->unhtmlentities($TMPL->tagdata) : FALSE ;
      $version = ($TMPL->fetch_param('version') !== FALSE) ? $TMPL->fetch_param('version') : 'niv' ;

      // if there is a youversion tag in the text
   	if( strpos( $data, '[youversion]' ) !== FALSE ) {

   		// exlode the text into an array
   		$data = explode( '[youversion]', $data );
         
   		// loop through array
   		foreach( $data as $row ) {

   			// if this row has a
   			if( strpos( $row, '[/youversion]' ) !== FALSE ) {

   				// explode this return in case there is more text after the tag
   				$row_exploded = explode( '[/youversion]', $row );

   				// trim away closing tag
   				$row_exploded[0] = preg_replace( '/\[\/youversion\].*/', '', $row_exploded[0] );


   				// change book name to abbreviated book name
   				foreach( $osis as $key => $value ) {
   					if( stristr( $row_exploded[0], $key ) !== FALSE ) {
   						$reference_link = str_replace( $key, $value . '/', $row_exploded[0] );
   						break;
   					}
   				}

   				// change : to /
   				$reference_link = str_replace( ':', '/', $reference_link );

   				// remove any spaces
   				$reference_link = str_replace( ' ', '', $reference_link );

   				// put the text in the tag in a link
   				$row_exploded[0] = '<a target="_blank" href="http://www.youversion.com/bible/' . $version . '/' . $reference_link . '">' . $row_exploded[0] . '</a>';

   				// put the link and any text after it back together
   				$row = implode( $row_exploded );

   			}

   			$this->return_data = $row;

   		}
   		
		} else {
		   $this->return_data = $data;
		}

   }

   /**
    * Plugin Usage
    */

   // This function describes how the plugin is used.
   //  Make sure and use output buffering

   function usage()
   {
      ob_start(); 
?>

====================
Overview
==============================
The YouVersion ExpressionEngine plugin gives you the ability to quickly link to Bible verses using a simple tag structure that's familiar to ExpressionEngine. This plugin was originally written by Dan Frist (@danfrist) for WordPress and was ported over to ExpressionEngine by Erik Reagan (@erikreagan).



====================
Usage
==============================

By wrapping your content in {exp:youversion} tags you can automatically link up scripture references to youversion.com.

- Examples

In-template reference:
{exp:youversion}[youversion]John 3:16[/youversion]{/exp:youversion}

Link all references in a custom field:
{exp:youversion version="nkjv"}{content_body}{/exp:youversion}

- Versions / Translations

By default NIV will be used for the version. You can specify your version preference by adding the parameter version="" (as seen above) and using one of these versions:

bg1940: Bulgarian 1940 
csbkr: Czech Bible Kralicka 1613 
elb: Elberfelder Bibel 
delut: Luther Bible 1545 
asv: American Standard Version 
amp: Amplified Bible 
cev: Contemporary English Version 
esv: English Standard Version 
gwt: GOD'S WORD Translation 
hcsb: Holman Christian Standard Bible 
kjv: King James Version 
net: New English Translation 
nasb: New American Standard Bible 
ncv: New Century Version 
niv: New International Version 
nkjv: New King James Version 
tniv: Today's New International Version 
nlt: New Living Translation 
msg: The Message 
web: World English Bible 
lbla: La Biblia de las Americas 
nblh: Nueva Biblia de los Hispanos 
nvi: Nueva Version Internacional 
rves: Reina-Valera Antigua 
finpr: Finnish 1938 
lsg: Louis Segond 
idbar: Terjemahan Baru 
itriv: Italian Riveduta (1927) 
ja1955: Colloquial Japanese (1955) 
sv1750: Statenvertaling 
norsk: Det Norsk Bibelselskap 1930 
aa: Almeida Atualizada 
rmnn: Romanian Cornilescu 1928 
sven: Svenska 1917 
vi1934: 1934 Vietnamese Bible
  


====================
Notes
==============================

Remember to spell the verse reference properly and use the commonly accepted format for Bible references (ie. John 3:16). The reference formats that work are "John 3:16" and "John 3:16-18". References that use commas (ie. John 3:16,18) or multi-chapter spans (ie. John 3:16-4:5) will not work and will result in a link that leads to a dead page on YouVersion.com.



====================
Acceptable book names
==============================

- Old Testament

   Genesis
   Exodus
   Leviticus
   Numbers
   Deuteronomy
   Joshua
   Judges
   Ruth
   1 Samuel
   2 Samuel
   1 Kings
   2 Kings
   1 Chronicles
   2 Chronicles
   Ezra
   Nehemiah
   Esther
   Job
   Psalms
   Proverbs
   Ecclesiastes
   Song of Solomon
   Isaiah
   Jeremiah
   Lamentations
   Ezekiel
   Daniel
   Hosea
   Joel
   Amos
   Obadiah
   Jonah
   Micah
   Nahum
   Habakkuk
   Zephaniah
   Haggai
   Zechariah
   Malachi

- New Testament

   Matthew
   Mark
   Luke
   John
   Acts
   Romans
   1 Corinthians
   2 Corinthians
   Galatians
   Ephesians
   Philippians
   Colossians
   1 Thessalonians
   2 Thessalonians
   1 Timothy
   2 Timothy
   Titus
   Philemon
   Hebrews
   James
   1 Peter
   2 Peter
   1 John
   2 John
   3 John
   Jude
   Revelation





====================
About YouVersion
==============================

YouVersion is an online Bible tool that offers 41 Bible versions in over 20 languages. At YouVersion.com, you can read the Bible in an innovative format, share your Bible reading experience with your friends, create Contributions with rich media and Journal entries that are tied to passages of Scripture, or subscribe to one of our 22 Bible reading plans.

YouVersion.com has given you the ability to engage with Scripture like never before, and with YouVersion mobile you have access to the Bible, corresponding contributions, reading plans, and online community no matter where you are. Our YouVersion mobile apps put the YouVersion experience in the palm of your hand. Apps are available for the iPhone, iPod Touch, Blackberry, Android, Palm's WebOS, Java, and the mobile web.

Learn more about our mobile Bible applications at http://youversion.com/mobile


<?php
      $buffer         = ob_get_contents();

      ob_end_clean(); 

      return $buffer;
   }
   // END

}


/* End of file pi.youversion.php */
/* Location: ./system/plugins/pi.er_youversion.php */