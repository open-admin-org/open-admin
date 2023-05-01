<?php

namespace OpenAdmin\Admin\Form\Field\Traits;

use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Resourceable;
use OpenAdmin\Admin\Widgets\Modal;

trait HasResource
{
    /**
     * @var string
     */
    protected $modalID;

    /**
     * @var string
     */
    protected $resourceableClass;

    /**
     * @var object
     */
    protected $resourceable;

    /**
     * @var string
     */
    protected $grid_message;

    /**
     * @var string|null
     */
    public $first_save_message;

    /**
     * BelongsToRelation constructor.
     *
     * @param string $column
     * @param array  $arguments
     */
    public function __construct($column, $arguments = [])
    {
        $this->setResourceable($arguments[0]);

        parent::__construct($column, array_slice($arguments, 1));
    }

    public function hideRelationColumn($set = true)
    {
        $this->hideRelationColumn = $set;

        return $this;
    }

    /**
     * @param string $selectable
     */
    protected function setResourceable($resourceableClass)
    {
        if (!class_exists($resourceableClass) || !is_subclass_of($resourceableClass, Resourceable::class)) {
            throw new \InvalidArgumentException(
                "[Class [{$resourceableClass}] must be a sub class of OpenAdmin\Admin\Resourceable"
            );
        }

        $this->resourceableClass = $resourceableClass;
    }

    /**
     * @return string
     */
    public function getResourceable()
    {
        $this->resourceable = new $this->resourceableClass();

        return $this->resourceable;
    }

    /**
     * @return $this
     */
    public function addModal()
    {
        $trans = [
            'create' => admin_trans('admin.create'),
            'choose' => admin_trans('admin.choose'),
            'cancal' => admin_trans('admin.cancel'),
            'submit' => admin_trans('admin.submit'),
        ];

        $footer = <<<HTML
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{$trans['cancal']}</button>
        <button type="button" class="btn btn-primary submit">{$trans['submit']}</button>
        HTML;

        $modal = new Modal([
            'id'     => $this->modalID,
            'title'  => $trans['create'],
            'footer' => $footer,
        ]);

        $html = $modal->render();
        Admin::html($html);

        return $this;
    }

    public function addScript()
    {
        $column                  = $this->column();
        $this->modalInitFunction = str_replace('-', '_', $this->modalID).'_init';

        $script = <<<JS

            ;(function () {
                var grid,table,gridHolder,addBtn;

                function initGrid_{$column}(){
                    grid = document.querySelector('.{$this->relation_prefix}{$column}');
                    table = grid.querySelector('.grid-table');
                    gridHolder = grid.querySelector('.grid-holder');
                    addBtn = grid.querySelector(".grid-create-btn");

                    addBtn.setAttribute("target","_modal");
                    addBtn.dataset.modal = '#{$this->modalID}';
                    addBtn.dataset.modalInit = '{$this->modalInitFunction}';

                    table.querySelectorAll('.__actions__div .row-edit, .__actions__div .row-show').forEach(btn =>{
                        btn.setAttribute("target","_modal");
                        btn.dataset.modal = '#{$this->modalID}';
                        btn.dataset.modalInit = '{$this->modalInitFunction}';
                    });
                }

                window.{$this->modalInitFunction} = function (){

                    var modal = document.querySelector('#{$this->modalID}');

                    modal.querySelectorAll('script').forEach((script) => {
                        var src = script.getAttribute('src');
                        if (src) {
                            script = document.createElement('script');
                            script.type = 'text/javascript';
                            script.src = src;
                            modal.appendChild(script);
                        } else {
                            eval(script.innerText);
                        }
                    });

                    var title = modal.querySelector('.modal-body .card-title');
                    var hide_submit = false;
                    if (title){
                        modal.querySelector('.modal-header .modal-title').innerHTML = title.innerHTML;
                        if (title.innerHTML == "Detail"){
                            hide_submit = true;
                        }
                    }

                    var submit = modal.querySelector('.modal-footer .btn.submit');
                    submit.classList.toggle("d-none", hide_submit);
                    if (!submit.classList.contains("has-onclick")){
                        submit.addEventListener("click",function(event){

                            event.preventDefault();
                            event.stopPropagation();

                            var form = document.querySelector('#{$this->modalID} .modal-body form');

                            admin.form.submit(form,function(result){
                                gridHolder.innerHTML = result.data;
                                var modal = bootstrap.Modal.getOrCreateInstance(document.querySelector('#{$this->modalID}'));
                                modal.hide();
                                initGrid_{$column}();
                            });

                            return false;
                        });
                    }
                    submit.classList.add("has-onclick");
                }

                initGrid_{$column}();

            })();
        JS;

        Admin::script($script);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function render()
    {
        $this->modalID = sprintf('modal-selector-%s', $this->getElementClassString());
        $this->getResourceable();
        $this->addScript()->addModal();

        $parent_id = $this->form->model->id;
        if (empty($parent_id)) {
            $this->grid_message = $this->first_save_message ?? __('Save, before adding items');
        }

        $this->addVariables([
            'relation_prefix' => $this->relation_prefix,
            'grid_message'    => $this->grid_message,
            'grid'            => $this->resourceable->makeGrid($parent_id),
            'refresh_url'     => $this->resourceable->getLoadUrl($parent_id),
            'options'         => [],
        ]);

        return parent::fieldRender();
    }
}
