<button class="btn btn-primary pull-right" wire:click="show_form_add" type="button">{{ trans('AdminSb.add_new_invoice') }}</button><br><br>
<div class="table-responsive">
    <table class="table text-md-nowrap" id="example1" data-page-length="50" style="text-align: center">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('AdminSb.service_name') }}</th>
                <th>{{ trans('AdminSb.patient_name') }}</th>
                <th>{{ trans('AdminSb.invoice_date') }}</th>
                <th>{{ trans('AdminSb.doctor_name') }}</th>
                <th>{{ trans('AdminSb.department') }}</th>
                <th>{{ trans('AdminSb.service_price') }}</th>
                <th>{{ trans('AdminSb.discount_value') }}</th>
                <th>{{ trans('AdminSb.tax_percentage') }}</th>
                <th>{{ trans('AdminSb.tax_value') }}</th>
                <th>{{ trans('AdminSb.total_with_tax') }}</th>
                <th>{{ trans('AdminSb.invoice_type') }}</th>
                <th>{{ trans('AdminSb.Processes') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($single_invoices as $single_invoice)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $single_invoice->Service->name }}</td>
                    <td>{{ $single_invoice->Patient->name }}</td>
                    <td>{{ $single_invoice->invoice_date }}</td>
                    <td>{{ $single_invoice->Doctor->name }}</td>
                    <td>{{ $single_invoice->Section->name }}</td>
                    <td>{{ number_format($single_invoice->price, 2) }}</td>
                    <td>{{ number_format($single_invoice->discount_value, 2) }}</td>
                    <td>{{ $single_invoice->tax_rate }}%</td>
                    <td>{{ number_format($single_invoice->tax_value, 2) }}</td>
                    <td>{{ number_format($single_invoice->total_with_tax, 2) }}</td>
                    <td>{{ $single_invoice->type == 1 ? trans('AdminSb.cash') : trans('AdminSb.deferred') }}</td>
                    <td>
                        <button wire:click="edit({{ $single_invoice->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                            data-target="#delete_invoice" wire:click="prepareForDelete({{ $single_invoice->id }})"
                            title="{{ trans('AdminSb.delete_invoice') }}">
                            <i class="fa fa-trash"></i></button>
                        <button wire:click="print({{ $single_invoice->id }})" class="btn btn-primary btn-sm"><i class="fas fa-print"></i></button>
                    </td>
                </tr>
            @endforeach
    </table>

    @include('livewire.single_invoices.delete')
</div>
