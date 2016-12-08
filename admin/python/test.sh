#§/bin/bash
folder="/mnt/Beijing/AddRemoverFiles/Reklamer/tv2/single/"

echo "cleaning up"
#rm -rf ./results ./temp_audio ./dejavu_test_results ./results-compare.log

#echo "fingerprinting"
python dejavu.py

echo "running test:"
python runtests.py \
	--secs 5 \
	--temp ./temp_audio \
	--log-file ./results/dejavu-test.log \
	--padding 8 \
	--seed 42 \
	--results ./results \
	$folder
