@if(config('custom.PKG_DEV'))
    <?php $serial_number_prefix = '/packages/abs/serial-number-pkg/src';?>
@else
    <?php $serial_number_prefix = '';?>
@endif

 <script type="text/javascript">
    var serial_number_type_list_template_url = "{{URL::asset($serial_number_prefix .'/public/angular/serial-number-pkg/pages/serial-number-type/list.html')}}";
    var serial_number_type_get_form_data_url = "{{url('serial-number-pkg/serial-number-types/add/')}}";
    var serial_number_type_form_template_url = "{{URL::asset($serial_number_prefix .'/public/angular/serial-number-pkg/pages/serial-number-type/form.html')}}";
    var serial_number_type_delete_data_url = "{{url('serial-number-pkg/serial-number-types/delete/')}}";
</script>
<script type="text/javascript" src="{{URL::asset($serial_number_prefix .'/public/angular/serial-number-pkg/pages/serial-number-type/controller.js?v=2')}}"></script>
 <!-- ------------------------------------------------------------------------------------------ -->
 <!-- ------------------------------------------------------------------------------------------ -->
<script type="text/javascript">
    var serial_number_segment_list_template_url = "{{URL::asset($serial_number_prefix .'/public/angular/serial-number-pkg/pages/serial-number-segment/list.html')}}";
    var serial_number_segment_get_form_data_url = "{{url('serial-number-pkg/serial-number-segments/add/')}}";
    var serial_number_segment_form_template_url = "{{URL::asset($serial_number_prefix .'/public/angular/serial-number-pkg/pages/serial-number-segment/form.html')}}";
    var serial_number_segment_delete_data_url = "{{url('serial-number-pkg/serial-number-segments/delete/')}}";
</script>
<script type="text/javascript" src="{{URL::asset($serial_number_prefix .'/public/angular/serial-number-pkg/pages/serial-number-segment/controller.js?v=2')}}"></script>
 <!-- ------------------------------------------------------------------------------------------ -->
 <!-- ------------------------------------------------------------------------------------------ -->

 <script type="text/javascript">
    var serial_number_group_list_template_url = "{{URL::asset($serial_number_prefix .'/public/angular/serial-number-pkg/pages/serial-number-group/list.html')}}";
    var serial_number_group_get_form_data_url = "{{url('serial-number-pkg/serial-number-groups/add/')}}";
    var serial_number_group_form_template_url = "{{URL::asset($serial_number_prefix .'/public/angular/serial-number-pkg/pages/serial-number-group/form.html')}}";
    var serial_number_group_delete_data_url = "{{url('serial-number-pkg/serial-number-groups/delete/')}}";
    var get_branch_based_state_url = "{{url('serial-number-pkg/serial-number-groups/getBranch/')}}";
    var get_segment_based_on_change_data_url = "{{url('serial-number-pkg/serial-number-groups/get-segment/')}}";
    var get_serial_number_group_filter_url = "{{route('getSerialNumberGroupFilter')}}";
</script>
<script type="text/javascript" src="{{URL::asset($serial_number_prefix .'/public/angular/serial-number-pkg/pages/serial-number-group/controller.js?v=2')}}"></script>
