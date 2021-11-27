<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SearchTest extends TestCase
{
	private $serverUrl;

	protected function setUp(): void
	{
		$this->serverUrl = "http://localhost:8000/server/";
	}

	/**
	 * @covers @codeCoverageIgnore
	 */
	public function testValidRequestSearchThread() : void {
		$response = file_get_contents($this->serverUrl."middlewares/ThreadMiddleware.class.php?query=test&threadSearch=true");
		$response = json_decode($response);
		
		$this->assertSame("test", $response[0]->thread_title);
		$this->assertSame("test", $response[0]->thread_url);
	}

	/**
	 * @covers @codeCoverageIgnore
	 */
	public function testValidRequestSearchComment() : void {
		$response = file_get_contents($this->serverUrl."middlewares/CommentMiddleware.class.php?query=test&commentSearch=true");
		$response = json_decode($response);
		$this->assertSame(0, $response[0]->isVoted);
		$this->assertSame(0, $response[0]->typeVote);
	}
}
?>