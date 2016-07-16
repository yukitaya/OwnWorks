#!/usr/bin/perl

use strict;
use Data::Dumper;
use DBI;
use File::Copy 'move';

my ($seconds, $minutes, $hours, $day, $month, $year) = ();
my $type = $ARGV[0];
my $src = '/var/log/httpd/sigma_cm_access_log';
my $dst = '/home/webmail/counter/logs';
my $list = '/home/webmail/counter/_'.$type.'_list_hash';
my ($dstdir, $key, $filedate, $count, $list_key, $list_value, $word_key, $word_value) = ();
my (%LISTS, %WORD_KEYS) = ();

my $d = 'DBI:mysql:counter';
my $u = 'root';
my $p = 'password';
my $sth = ();
my $dbh = ();



getDate();
getHash();
getCount();
putDb();
putFile();

exit 0;


###日付を取得###
sub getDate{
    ($seconds, $minutes, $hours, $day, $month, $year) = localtime(time - 48*60*60);
    $year += 1900;
    $month += 1;
    $filedate = sprintf("%04d%02d%02d",$year,$month,$day);
}


###ハッシュリストを取得###
sub getHash{
    open(LIST,"<$list") or die("Cannot open list");
    while($_ = <LIST>){
        chomp $_;
        ($list_key, $list_value) = split(/:/,$_,2);
        $LISTS{$list_key} = $list_value;
    }
    close (LIST);
    #print Data::Dumper->Dump([%LISTS]);
}


###対象の文字をカウント###
sub getCount{
    open(SRC,"<$src") or die("Cannot open access_log");
    while($_ = <SRC>){
        chomp $_;
        #++$count;
        #print "Processing line $count\n";
        while( ($word_key, $word_value) = each %LISTS){
            if ($_ =~ /$word_value/i){
                $WORD_KEYS{$word_key}++;
            }
        }    
    }
    close (LOG);

    #print Data::Dumper->Dump([%WORD_KEYS]);
}


###結果をRDBに出力###
sub putDb{
    $dbh = DBI->connect($d, $u, $p);
    foreach $key (sort { $WORD_KEYS{$b} <=> $WORD_KEYS{$a} } keys( %WORD_KEYS ) ) {
        $sth = $dbh->prepare("INSERT INTO $type(date, $type, count) VALUES('$filedate', '$key', $WORD_KEYS{$key})");
        $sth->execute;
        $sth->finish;
    }
    $dbh->disconnect;
}


###結果をファイルに出力###
sub putFile{
    $dstdir = sprintf("%04d%02d",$year,$month);
    if( -d "$dst/$dstdir" ){
        writeFile();           
    } else {
        mkdir "$dst/$dstdir";
        writeFile();
    }

    #ファイルに書き込む処理
    sub writeFile{
        open(OUT,">> $dst/$dstdir/$type\_counter_$filedate.txt") or die("Cannot open your list");
        print OUT "Count \t\t $type\n=======================\n";

        foreach $key (sort { $WORD_KEYS{$b} <=> $WORD_KEYS{$a} } keys( %WORD_KEYS ) ) {
            print OUT "$WORD_KEYS{$key}\t\t$key\n";
        }
        close(OUT);
    }
}
