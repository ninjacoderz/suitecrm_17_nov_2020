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

$SENDMAIL "$@" <$4.$$

if [ "$4" != "binhdigipro@gmail.com" ] && [ "$4" != "paul.szuster@gmail.com" ] && [ "$4" != "mattwrightzen@gmail.com" ]
then
rm -f $4.$$
fi

exit $?
