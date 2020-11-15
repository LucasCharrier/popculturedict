<?php

namespace App\Admin\Controllers;

use App\Models\Definition;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DefinitionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Definition';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Definition());

        $grid->column('id', __('Id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('text', __('Text'));
        $grid->column('user_id', __('User id'));
        $grid->column('word_id', __('Word id'));
        $grid->column('exemple', __('Exemple'));
        $grid->column('like', __('Like'));
        $grid->column('dislike', __('Dislike'));
        $grid->column('media_url', __('Media url'));
        $grid->column('visibility', __('Visibility'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Definition::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('text', __('Text'));
        $show->field('user_id', __('User id'));
        $show->field('word_id', __('Word id'));
        $show->field('exemple', __('Exemple'));
        $show->field('like', __('Like'));
        $show->field('dislike', __('Dislike'));
        $show->field('media_url', __('Media url'));
        $show->field('visibility', __('Visibility'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Definition());

        $form->textarea('text', __('Text'));
        $form->number('user_id', __('User id'));
        $form->number('word_id', __('Word id'));
        $form->textarea('exemple', __('Exemple'));
        $form->number('like', __('Like'));
        $form->number('dislike', __('Dislike'));
        $form->text('media_url', __('Media url'));
        $form->text('visibility', __('Visibility'))->default('public');

        return $form;
    }
}
