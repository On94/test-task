<?php


namespace App\Http\Controllers\API;

use App\Core\Request;
use App\Core\Validator;
use App\Services\BookService;
use App\Services\GuzzleService;
use App\Services\UserService;

/**
 * Class BooksController
 * @package App\Http\Controllers\API
 */
class BooksController extends ApiBaseController
{
    /**
     * @var string
     */
    private $countryApi = 'http://country.io/continent.json';

    /**
     * @var string
     */
    private $timezoneApi = 'http://worldtimeapi.org/api/timezone';

    /**
     * @var Validator
     */
    private Validator $validator;

    /**
     * @var GuzzleService
     */
    private GuzzleService $guzzleService;

    /**
     * @var BookService
     */
    private BookService $bookService;

    /**
     * BooksController constructor.
     * @param UserService $userService
     * @param Validator $validator
     * @param GuzzleService $guzzleService
     * @param BookService $bookService
     * @throws \Lindelius\JWT\Exception\InvalidJwtException
     * @throws \Lindelius\JWT\Exception\JwtException
     */
    public function __construct(
        UserService $userService,
        Validator $validator,
        GuzzleService $guzzleService,
        BookService $bookService
    )
    {
        $this->validator = $validator;
        $this->guzzleService = $guzzleService;
        $this->guzzleService = $guzzleService;
        $this->bookService = $bookService;
        $this->verifyToken($userService);
    }

    /**
     * @param Request $request
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(Request $request):void
    {
        $data = $request->only(['first_name', 'last_name', 'phone_number', 'country_code', 'timezone']);
        $data = $this->checkData($data);
        if ($this->validator->validation()) {
            $this->bookService->store($data);
            self::APIResponse(
                [
                    'success' => true,
                    'message' => 'New address has been saved.'
                ]
            );
        }
        self::APIResponse($this->validator->getApiErrors());
    }

    /**
     * @param Request $request
     */
    public function retrieve(Request $request): void
    {
        $id = intval($request->get('id'));
        $response = $this->bookService->retrieve($this->user['id'], $id);
        self::APIResponse($response ? [
            'success' => true,
            'data' => $response
        ] : [
            'success' => false,
            'message' => 'No data exists'
        ]);
    }

    /**
     * @param Request $request
     */
    public function delete(Request $request)
    {
        $id = intval($request->get('id'));
        if ($id && $this->bookService->delete($this->user['id'], $id)) {
            self::APIResponse([
                'success' => true,
                'message' => 'Has been successfully deleted.'
            ]);
        }
        self::APIResponse([
            'success' => false,
            'message' => 'No data exists with this ID.'
        ]);
    }

    /**
     * @param Request $request
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(Request $request):void
    {
        $data = $request->put();
        $id = intval($request->get('id'));
        $data = $this->checkData($data);
        if ($this->validator->validation() && $this->bookService->update($this->user['id'], $id, $data)) {
            self::APIResponse([
                'success' => true,
                'message' => 'Has been successfully updated.'
            ]);
        }
        self::APIResponse($this->validator->getApiErrors());
    }


    /**
     * @param array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkData(array $data): array
    {
        $countries = $this->guzzleService->getData('GET', $this->countryApi);
        $timezones = $this->guzzleService->getData('GET', $this->timezoneApi);
        if (empty($data['country_code']) || empty($countries[trim($data['country_code'])])) $data['country_code'] = null;
        if (empty($data['timezone']) || !in_array($data['timezone'], $timezones)) $data['timezone'] = null;
        $this->validator->setRules([
            'first_name,last_name, phone_number' => ['required', 'message' => '%s cannot be blank.'],
            'timezone,country_code' => ['exists'],
            'first_name, phone_number,last_name' => ['string', 'max' => 255, 'message' => 'field is not correct.'],
        ])->setData($data);
        $data['user_id'] = $this->user['id'] ?? null;
        return $data;
    }
}

