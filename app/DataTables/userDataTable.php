<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class userDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" name="selected_rows[]" value="'.$row->id.'">';
            })
            ->addColumn('action', function ($row) {
                // Edit Button
                $editBtn = '<a href="'.route('user.edit', $row->id).'" class="btn btn-primary btn-sm">
                    <i class="fas fa-pencil-alt"></i>
                </a>';

                // View Button
                $viewBtn = '<a href="'.route('user.show', $row->id).'" class="btn btn-success btn-sm">
                    <i class="fas fa-eye"></i>
                </a>';

                // Delete Button
                $deleteBtn = '';
                if (Auth::id() != $row->id) {
                    $deleteBtn = '<form id="deleteForm-blog-'.$row->id.'"
                                action="'.route('user.destroy', $row->id).'"
                                method="POST" style="display:inline;">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="handleDelete(\'deleteForm-blog-'.$row->id.'\')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>';
                }
                //for the password button
                $passwordBtn = '<a href="'.route('password', $row->id).'" class="btn btn-secondary btn-sm">
                   <i class="fas fa-lock"></i>
                </a>';

                // Combine all buttons
                return $viewBtn.' '.$editBtn.' '.$deleteBtn.''.$passwordBtn;
            })
            ->addColumn('image', function ($row) {
                // Display image with a small thumbnail
                if ($row->image) {
                    return '<a href="'.asset('storage/images/resized/800px_'.basename($row->image)).'" 
                            data-fancybox="gallery" 
                            data-caption="'.$row->title.'">
                            <img src="'.asset('storage/images/resized/100px_'.basename($row->image)).'" 
                                 alt="'.$row->title.'" 
                                 style="width: 50px; height: auto;">
                        </a>';
                } else {
                    return '<p>No image available</p>';
                }
            })
            ->addColumn('status', function ($row) {
                if (Auth::id() != $row->id) {

                    return '<label for="status'.$row->id.'" class="form-label"><strong></strong></label>
                <div class="form-check form-switch">
                    <input class="form-check-input" 
                           type="checkbox" role="switch"
                           id="status'.$row->id.'" name="status"
                           data-id="'.$row->id.'" value="1"
                           '.($row->status ? 'checked' : '').'>
                    <label class="form-check-label" for="status'.$row->id.'"></label>
                </div>';
                } else {
                    return '<button class="btn btn-success btn-sm">Active</button>';
                }
            })

            ->rawColumns(['action', 'image', 'status', 'checkbox']) // Mark columns as raw HTML
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('blogs-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('frt<"d-flex justify-content-between align-items-center" l ip>')
            ->lengthMenu([[5, 10, 15, 20, 50], [5, 10, 15, 20, 50]])
            ->orderBy(1);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('checkbox')
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->title('<input type="checkbox" id="select-all">')
                ->width(30),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-left'),
            Column::make('name'),
            Column::make('email'),
            Column::make('phone'),
            Column::make('image')
                ->width(50),
            Column::make('status')
                ->exportable(false)
                ->printable(false)
                ->width(50),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'user_'.date('YmdHis');
    }
}
