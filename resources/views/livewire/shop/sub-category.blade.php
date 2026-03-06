<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Sub Categories</h4>
            </div>
        </div>
    </div>

    @include('modals.sub-category-delete-modal')

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="submit" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="category_id" wire:model="category_id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <center>
                                        @if ($image)
                                            <img src="{{ $image->temporaryUrl() }}" alt="preview" width="150"
                                                height="120" class="p-2">
                                        @elseif ($image_preview)
                                            <img src="{{ Storage::disk('spaces')->url($image_preview) }}" alt="preview"
                                                width="150" height="120" class="p-2">
                                        @else
                                            <img src="{{ asset('assets/images/dummy.jpeg') }}" alt="preview"
                                                width="150" height="120" class="p-2">
                                        @endif
                                    </center>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="formrow-email-input" class="form-label">{{ __('Cover Photo') }}</label>
                                    <input type="file" name="image" wire:model="image" value="{{ old('image') }}"
                                        class="form-control">
                                    @error('image')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Select Category') }}</label>
                                    <select name="category" wire:model.live="category" class="form-control">
                                        <option>-- select --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="formrow-email-input"
                                        class="form-label">{{ __('Sub Category Name') }}</label>
                                    <input type="text" name="name" wire:model.defer="name"
                                        value="{{ old('name') }}" class="form-control" id="formrow-email-input">
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary w-md">{{ __('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.I</th>
                                <th>Cover</th>
                                <th>Sub category</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sub_categories as $subcategory)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <a target="_blank" href="{{ Storage::disk('spaces')->url($subcategory->cover) }}">
                                            <img src="{{ Storage::disk('spaces')->url($subcategory->cover) }}" alt="image"
                                                width="50" height="50">
                                        </a>
                                    </td>
                                    <td>{{ $subcategory->name }}</td>
                                    <td>{{ $subcategory->category->name }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary waves-effect"
                                            wire:click="edit({{ $subcategory->id }})">
                                            {{ __('View / Edit') }}
                                        </button>

                                        <button class="btn btn-sm btn-light waves-effect text-danger"
                                            data-bs-toggle="modal" data-bs-target="#delete_modal"
                                            wire:click="delete({{ $subcategory->id }})">
                                            {{ __('Delete') }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
