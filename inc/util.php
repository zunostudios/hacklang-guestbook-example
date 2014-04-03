<?hh

class FlatFile {
	private string $filename;
		
	public function __construct($f) {
		$this->filename = $f;
	}

	public function linesToComments() : Vector<CommentLine> {
		$buffy = file_get_contents($this->filename);
		
		$lines = explode("\n", $buffy);
		
		// Here's how we would use a map in PHP
		// since array isn't an object, it doesnt have a map
		// method, so we have to use array_map

		$pairs = array_map(function ($x) {
			$g = explode("\t", $x);
			if(count($g) > 1) {
			
			return new CommentLine($g[0], $g[1]);	
			}
		}, $lines);

		return ( ( new Vector() )
				->fromArray($pairs)
				->filter(($x) ==> $x !== NULL ));
	}

	public function writeComment($name, $comment) {
		file_put_contents($this->filename, sprintf("%s\t%s\n", $name, htmlspecialchars($comment)), FILE_APPEND);
	}
} 

class GuestBook {
	private Vector<CommentLine> $comments;
	
	public function __construct(...) 
	{
		$this->comments = new Vector<CommentLine>();
		$the_comments = func_get_args();
		foreach ($the_comments as $e) {
			$this->comments->add($e);
		 }	
	}

	public function addVector($v) {
		$this->comments->addAll($v);
	}

	public function html_out() : void
	{
		$otherVector = $this->comments->values();
		
		// Here's how we would use a map using Hack's new Vector class
		// Since map is not supposed to affect the original collection,
		// it returns a new collection, just like array_map

	    $buffer = $otherVector->map( 
	       // A lambda expression that takes in a comment and
	       // formats it.
	  	   ($c) ==> sprintf("<div class=\"comment\"><p><strong>%s</strong></p><p>%s</p></div>", $c->title, $c->body)
		);
		
		foreach($buffer as $line) {
			echo $line;
		}
	}
	
} 

// We're going to represent comments with a plain old object
class CommentLine {
	public function __construct(string $title, string $body) {
		$this->title = $title;
 		$this->body = $body;
	}	
}