#!/usr/bin/perl -w
 
use strict;
use Spreadsheet::ParseExcel;

my $parser   = Spreadsheet::ParseExcel->new();
my $workbook = $parser->parse('test.xlsx');