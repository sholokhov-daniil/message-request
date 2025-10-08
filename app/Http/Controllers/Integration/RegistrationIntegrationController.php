<?php

namespace App\Http\Controllers\Integration;

use App\Core\Models\Bot;
use App\Core\Models\User;
use App\Core\Response\HttpResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Integration\RegistrationRequest;
use App\Http\Resources\ErrorResource;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Throwable;

class RegistrationIntegrationController extends Controller
{
    public function __invoke(RegistrationRequest $request, User $user)
    {
        $status = 200;
        $result = new HttpResponse(null);

        try {
            $data = $request->validated();
            $data['user_id'] = $user->id;

            if (Bot::query()->where('token', $data['token'])->exists()) {
                $result->addError('Токен уже зарегистрирован');
            } else {
                /** @var Bot $integration */
                $integration = Bot::query()->create($data);
                $result->setData(['id' => $integration->id]);
            }
        } catch (ValidationException $exception){
            $status = 400;

            foreach ($exception->validator->errors()->all() as $error) {
                $result->addError($error, 400);
            }
        } catch (QueryException $exception) {
            $status = 500;
            $result->addError($exception->getMessage(), 500);
        } catch (Throwable $e) {
            $status = 500;
            $result->addError('Произошла ошибка сохранения', 500);
        }

        return response()->json($result, $status);
    }
}
