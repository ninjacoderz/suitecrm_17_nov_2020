INSPECT_DIR="/var/www/suitecrm/email/"
SENDMAIL="/sbin/sendmail -G -i"

EX_TEMPFAIL=75
EX_UNAVAILABLE=69
#trap "rm -f in.$$" 0 1 2 3 15
cd $INSPECT_DIR || {
    echo $INSPECT_DIR does not exist; exit $EX_TEMPFAIL;
}
cat >$4.$$ || {
    echo Cannot save mail to file; exit $EX_TEMPFAIL;
}

#run php scrip to insert link here 
curl 'https://suitecrm.pure-electric.com.au/index.php?entryPoint=customAlterEmail&file_name='$4.$$;

$SENDMAIL "$@" <$4.$$

if [ "$2" == "binh.nguyen@pure-electric.com.au" ] || [ "$2" == "ross@pure-electric.com.au" ] || [ "$2" == "ross.munro@pure-electric.com.au" ] || [ "$2" == "paul.szuster@pure-electric.com.au" ] || 
	[ "$2" == "paul@pure-electric.com.au" ] || [ "$2" == "matthew.wright@pure-electric.com.au" ] || [ "$2" == "matthew@pure-electric.com.au" ] || [ "$2" == "lee.andrewartha@pure-electric.com.au" ] || [ "$2" == "james@pure-electric.com.au" ] || [ "$2" == "operations@pure-electric.com.au" ]
then 
	cp -v $4.$$ "forward/pure.electric.com.au@gmail.com.$$"
	#sed -i "s/$4/pure.electric.com.au@gmail.com/g" "forward/pure.electric.com.au@gmail.com.$$";
	#cd "forward"
	$SENDMAIL -f "$2" -- pure.electric.com.au@gmail.com <"forward/pure.electric.com.au@gmail.com.$$"
	rm -f "forward/pure.electric.com.au@gmail.com.$$"
	#cd ..
fi


if [ "$4" != "binhdigipro@gmail.com" ] && [ "$4" != "paul.szuster@gmail.com" ] && [ "$4" != "mattwrightzen@gmail.com" ]
then
rm -f $4.$$
fi

exit $?
