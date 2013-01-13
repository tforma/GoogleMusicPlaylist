	Script to parse extracted google music playlist files and copy music to a directory.
	Needs tab delimited text file extracted from google music web interface using 
	Chrome table extractor extenstion: 
	https://chrome.google.com/webstore/detail/table-capture/iebpjdmgckacbodjpijphcplhebcmeop?hl=en

	Usage: ./google_playlist.php <playlist_file> <music_src_dir> <dest_dir>

	<playlist_file> is the extracted text file to be parsed
	<music_src_dir> is the top level of your music dir, where file matches can be found
	<dest_dir> is the directory where matches will be copied into

	CopyRight: Tony Forma <tforma@gmail.com>
	License: Apache
