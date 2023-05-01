<?php

namespace OpenAdmin\Admin\Controllers;

trait HasResourceActions
{
    /**
     * Returns the form with possible callback hooks.
     *
     * @return \OpenAdmin\Admin\Form;
     */
    public function getForm()
    {
        $form = $this->form();
        if (method_exists($this, 'hasHooks') && $this->hasHooks('alterForm')) {
            $form = $this->callHooks('alterForm', $form);
        }

        return $form;
    }

    /**
     * Returns the form with possible callback hooks.
     *
     * @return \OpenAdmin\Admin\Show;
     */
    public function getShow($id = null)
    {
        $show = $this->detail($id);
        if (method_exists($this, 'hasHooks') && $this->hasHooks('alterShow')) {
            $show = $this->callHooks('alterShow', $show);
        }

        return $show;
    }

    /**
     * Returns the form with possible callback hooks.
     *
     * @return \OpenAdmin\Admin\Grid;
     */
    public function getGrid()
    {
        $grid = $this->grid();
        if (method_exists($this, 'hasHooks') && $this->hasHooks('alterGrid')) {
            $grid = $this->callHooks('alterGrid', $grid);
        }

        return $grid;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        return $this->getForm()->update($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->getForm()->store();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->getForm()->destroy($id);
    }
}
