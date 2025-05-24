<?php

namespace SuperAdmin\Admin\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use SuperAdmin\Admin\Actions\Action;
use SuperAdmin\Admin\Actions\GridAction;
use SuperAdmin\Admin\Actions\Response;
use SuperAdmin\Admin\Actions\RowAction;
use SuperAdmin\Admin\Widgets\Form;

class HandleController extends Controller
{
    /**
     * @return $this|mixed
     */
    public function handleForm(Request $request)
    {
        $form = $this->resolveForm($request);

        if ($errors = $form->validate($request)) {
            return back()->withInput()->withErrors($errors);
        }

        return $form->sanitize()->handle($request);
    }

    /**
     * @return Form
     *
     * @throws Exception
     */
    protected function resolveForm(Request $request)
    {
        if (! $request->has('_form_')) {
            throw new Exception('Invalid form request.');
        }

        $formClass = $request->get('_form_');

        if (! class_exists($formClass)) {
            throw new Exception("Form [{$formClass}] does not exist.");
        }

        /** @var Form $form */
        $form = app($formClass);

        if (! method_exists($form, 'handle')) {
            throw new Exception("Form method {$formClass}::handle() does not exist.");
        }

        return $form;
    }

    /**
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function handleAction(Request $request)
    {
        $action = $this->resolveActionInstance($request);

        $model = null;
        $arguments = [];

        if ($action instanceof GridAction) {
            $model = $action->retrieveModel($request);
            $arguments[] = $model;
        }

        if (! $action->passesAuthorization($model)) {
            return $action->failedAuthorization();
        }

        if ($action instanceof RowAction) {
            $action->setRow($model);
        }

        try {
            $response = $action->validate($request)->handle(
                ...$this->resolveActionArgs($request, ...$arguments)
            );
        } catch (Exception $exception) {
            return Response::withException($exception)->send();
        }

        if ($response instanceof Response) {
            return $response->send();
        }
    }

    /**
     * @return Action
     *
     * @throws Exception
     */
    protected function resolveActionInstance(Request $request)
    {
        if (! $request->has('_action')) {
            throw new Exception('Invalid action request.');
        }

        $actionClass = str_replace('_', '\\', $request->get('_action'));

        if (! class_exists($actionClass)) {
            throw new Exception("Form [{$actionClass}] does not exist.");
        }

        /** @var GridAction $form */
        $action = app($actionClass);

        if (! method_exists($action, 'handle')) {
            throw new Exception("Action method {$actionClass}::handle() does not exist.");
        }

        return $action;
    }

    /**
     * @param  Model|Collection|bool  $model
     * @return array
     */
    protected function resolveActionArgs(Request $request, $model = null)
    {
        $args = [$request];

        if (! empty($model)) {
            array_unshift($args, $model);
        }

        return $args;
    }

    /**
     * @return mixed|string|string[]
     */
    public function handleSelectable(Request $request)
    {
        $class = $request->get('selectable');
        $args = $request->get('args', []);

        $class = str_replace('_', '\\', $class);

        if (class_exists($class)) {
            /** @var \SuperAdmin\Admin\Grid\Selectable $selectable */
            $selectable = new $class(...array_values($args));

            return $selectable->render();
        }

        return $class;
    }

    /**
     * @return mixed|string|string[]
     */
    public function handleRenderable(Request $request)
    {
        $class = $request->get('renderable');
        $key = $request->get('key');

        $class = str_replace('_', '\\', $class);

        if (class_exists($class)) {
            /** @var Renderable $selectable */
            $renderable = new $class;

            return $renderable->render($key);
        }

        return $class;
    }
}
