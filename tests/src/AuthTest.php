<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class AuthTest extends TestCase
{
	private $serverUrl;

	protected function setUp(): void
	{
		$this->serverUrl = "http://localhost:8000/server/";
	}

	/**
	 * @covers @codeCoverageIgnore
	 */
	public function testEmptyAuthFields(): void
	{
		$response = file_get_contents($this->serverUrl."middlewares/UserMiddleware.class.php");
		$response = json_decode($response);
		
		$this->assertSame("Fields \"Email\" and \"Password\" are empty.", $response->data->message);
	}
	
	/**
	 * @covers @codeCoverageIgnore
	 */
	public function testInvalidGetAuthEmail(): void 
	{
		$response = file_get_contents($this->serverUrl."middlewares/UserMiddleware.class.php?email=invalid_username&password=invalid_password");
		$response = json_decode($response);
		$this->assertSame("Field \"Email\" is not valid.", $response->data->message);
	}

	/**
	 * @covers @codeCoverageIgnore
	 */
	public function testInvalidGetAuthPassword(): void 
	{
		$response = file_get_contents($this->serverUrl."middlewares/UserMiddleware.class.php?email=d3li0n@scroller.ca&password=invalid_password");
		$response = json_decode($response);
		$this->assertSame("Password is not correct.", $response->data->message);
	}

	/**
	 * @covers @codeCoverageIgnore
	 */
	public function testValidGetAuthCredentialsWithNotVerifiedEmail(): void
	{
		$response = file_get_contents($this->serverUrl."middlewares/UserMiddleware.class.php?email=test123@scroller.ca&password=testtest@L");
		$response = json_decode($response);
		$this->assertSame("Email is not verified", $response->data->message);
	}

	/**
	 * @covers @codeCoverageIgnore
	 */
	public function testValidGetAuthCredentialsWithVerifiedEmail(): void
	{
		$response = file_get_contents($this->serverUrl."middlewares/UserMiddleware.class.php?email=test456@scroller.ca&password=testtest@L");
		$response = json_decode($response);
		
		$this->assertSame(200, $response->response);
	}
}
?>