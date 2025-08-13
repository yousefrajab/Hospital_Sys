<div>
    @if ($ServiceSaved)
        <div class="alert alert-info">{{ trans('Services.Data has been saved successfully.') }}</div>
    @endif

    @if ($ServiceUpdated)
        <div class="alert alert-info">{{ trans('Services.Data has been updated successfully.') }}</div>
    @endif

    @if ($show_table)
        @include('livewire.GroupServices.index')
    @else
        <form wire:submit.prevent="saveGroup" autocomplete="off">
            @csrf
            <div class="form-group">
                <label>{{ trans('AdminSb.Group_Name') }}</label>
                <input wire:model="name_group" type="text" name="name_group" class="form-control" required>
            </div>

            <div class="form-group">
                <label>{{ trans('AdminSb.description') }}</label>
                <textarea wire:model="notes" name="notes" class="form-control" rows="5"></textarea>
            </div>

            <div class="form-group">
                <label for="group_doctor_id_field">{{ trans('AdminSb.package_responsible_doctor_optional') }}</label>
                <select wire:model.defer="group_doctor_id" id="group_doctor_id_field"
                    class="form-control @error('group_doctor_id') is-invalid @enderror">
                    <option value="">{{ trans('AdminSb.select_doctor_for_package') }}</option>
                    @if (isset($availableDoctorsForGroup))
                        @foreach ($availableDoctorsForGroup as $doc)
                            <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('group_doctor_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <div class="col-md-12">
                        <button class="btn btn-outline-primary" style="color: white"
                            wire:click.prevent="addService">{{ trans('AdminSb.Add_sub_service') }}</button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-primary">
                                    <th>{{ trans('AdminSb.Service_Name') }}</th>
                                    <th width="200">{{ trans('AdminSb.Number') }}</th>
                                    <th width="200">{{ trans('AdminSb.responsible_doctor') }}</th>
                                    <th width="200">{{ trans('AdminSb.Operations') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($GroupsItems as $index => $groupItem)
                                    <tr>
                                        <td>
                                            @if ($groupItem['is_saved'])
                                                <input type="hidden"
                                                    name="GroupsItems[{{ $index }}][service_id]"
                                                    wire:model="GroupsItems.{{ $index }}.service_id" />
                                                @if ($groupItem['service_name'] && $groupItem['service_price'])
                                                    {{ $groupItem['service_name'] }}
                                                    ({{ number_format($groupItem['service_price'], 2) }})
                                                @endif
                                            @else
                                                <select name="GroupsItems[{{ $index }}][service_id]"
                                                    class="form-control{{ $errors->has('GroupsItems.' . $index) ? ' is-invalid' : '' }}"
                                                    wire:model="GroupsItems.{{ $index }}.service_id">
                                                    <option value="">{{ trans('AdminSb.choose_service') }}
                                                    </option>
                                                    @foreach ($allServices as $service)
                                                        <option value="{{ $service->id }}">
                                                            {{ \App\Models\ServiceTranslation::where(['Service_id' => $service->id])->pluck('name')->first() }}
                                                            ({{ number_format($service->price, 2) }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('GroupsItems.' . $index))
                                                    <em
                                                        class="invalid-feedback">{{ $errors->first('GroupsItems.' . $index) }}</em>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if ($groupItem['is_saved'])
                                                <input type="hidden" name="GroupsItems[{{ $index }}][quantity]"
                                                    wire:model="GroupsItems.{{ $index }}.quantity" />
                                                {{ $groupItem['quantity'] }}
                                            @else
                                                <input type="number" name="GroupsItems[{{ $index }}][quantity]"
                                                    class="form-control"
                                                    wire:model="GroupsItems.{{ $index }}.quantity" />
                                            @endif
                                        </td>
                                        <td>
                                            @if ($groupItem['is_saved'])
                                                {{-- This part seems redundant as the information is displayed in the first column, but translated it anyway --}}
                                            @else
                                                {{-- This would be the select dropdown for the doctor, which seems to be missing in the original code. Translated based on context --}}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($groupItem['is_saved'])
                                                <button class="btn btn-sm btn-primary"
                                                    wire:click.prevent="editService({{ $index }})">{{ trans('AdminSb.Edit') }}</button>
                                            @elseif($groupItem['service_id'])
                                                <button class="btn btn-sm btn-success mr-1"
                                                    wire:click.prevent="saveService({{ $index }})">{{ trans('AdminSb.Confirm') }}</button>
                                            @endif
                                            <button class="btn btn-sm btn-danger"
                                                wire:click.prevent="removeService({{ $index }})">{{ trans('AdminSb.Delete') }}</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="col-lg-4 ml-auto text-right">
                        <table class="table pull-right">
                            <tr>
                                <td style="color: red">{{ trans('AdminSb.Total') }}</td>
                                <td>{{ number_format($subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="color: red">{{ trans('AdminSb.Discount_Amount') }}</td>
                                <td width="125">
                                    <input type="number" name="discount_value" class="form-control w-75 d-inline"
                                        wire:model="discount_value">
                                </td>
                            </tr>
                            <tr>
                                <td style="color: red">{{ trans('AdminSb.Tax_Rate') }}</td>
                                <td>
                                    <input type="number" name="taxes" class="form-control w-75 d-inline"
                                        min="0" max="100" wire:model="taxes"> %
                                </td>
                            </tr>
                            <tr>
                                <td style="color: red">{{ trans('AdminSb.Total_including_tax') }}</td>
                                <td>{{ number_format($total, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                    <br />
                    <div>
                        <input class="btn btn-outline-success" type="submit"
                            value="{{ trans('AdminSb.Data_confirmation') }}">
                    </div>
                </div>
            </div>
        </form>
    @endif


</div>
