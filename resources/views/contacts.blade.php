@extends('layouts.app')

@section('content')
<div id="vue_app">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('Contacts') }}
                        <button type="button" id="modal-contact" class="btn btn-success float-right" @click="openModal">Add new</button>
                    </div>
                    <div class="card-body">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="datatable">
                                    <thead class="bg-gray">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Contact Information</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <input type="hidden" name="id" v-model="id">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" v-model="name" class="form-control" id="name" placeholder="Enter name">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input type="email" v-model="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
              </div>
              <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" v-model="phone" class="form-control" id="phone" placeholder="Enter phone">
              </div>
              <div class="form-group">
                <label for="country">Country</label>
                <input type="text" v-model="country" class="form-control" id="country" placeholder="Enter Country">
              </div>
              <div class="form-group">
                <label for="city">City</label>
                <input type="text" v-model="city" class="form-control" id="city" placeholder="Enter City">
              </div>
              <div class="form-group">
                <label for="state">State</label>
                <input type="text" v-model="state" maxlength="2" class="form-control" id="state" placeholder="Enter State">
              </div>
              <div class="form-group">
                <label for="zip">Zip</label>
                <input type="text" v-model="zip" class="form-control" id="zip" placeholder="Enter zip">
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" @click="saveContact" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
</div>
<script type="text/javascript">
    var app = new Vue({
      el: '#vue_app',
      data: {
        name: null,
        email: null,
        phone: null,
        country: null,
        city: null,
        state: null,
        zip: null,
        id: null
      },
      methods: {
        validateInput() {
            if (!this.name) {
                swal ( "Oops" ,  "Name is required!" ,  "error" )
                return false;
            }
            if (!this.email) {
                swal ( "Oops" ,  "Email is required!" ,  "error" )
                return false;
            }

            //check if email is already exist
            if (!this.email) {
               axios.post('/check-email', {'email': this.email, 'id' : this.id})
                .then(response =>  {
                    if(response.data) {
                        swal ( "Oops" ,  "Email is already taken!" ,  "error" );
                        return false;
                    }
                })
                .catch(error => console.log(error));
            }


            if (!this.phone) {
                swal ( "Oops" ,  "Phone is required!" ,  "error" )
                return false;
            }
            return true;

        },
        openModal() {
            this.resetModal();
            $('#exampleModal').modal('toggle');
        },
        resetModal() {
            this.name = null;
            this.email = null;
            this.phone = null;
            this.country =  null;
            this.city = null;
            this.state = null;
            this.zip = null;
            this.id = null;
        },
        deleteContact(id) {
            swal({
              title: "Are you sure?",
              text: "Once deleted, you will not be able to recover this data!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                axios.delete('/contacts/'+id)
                .then(response =>  {
                    window.contact_datatable.ajax.reload();
                    swal("Poof! Your data file has been deleted!", {
                      icon: "success",
                    });
                }).catch(error => console.log(error));
              } else {
                swal("Your data file is safe!");
              }
            });

        },
        editContact(id) {
            axios.get('/contacts/'+id)
                .then(response =>  {
                    this.name = response.data.name;
                    this.email = response.data.email;
                    this.phone = response.data.phone;
                    this.country =  response.data.country;
                    this.city = response.data.city;
                    this.state = response.data.state;
                    this.zip = response.data.zip;
                    this.id = response.data.id;
                    $('#exampleModal').modal('toggle');
                })
                .catch(error => console.log(error));
        },
        saveContact() {
            if (!this.validateInput())
                return;
            var param = {
             'name': this.name,
             'email': this.email,
             'phone': this.phone,
             'country': this.country,
             'city': this.city,
             'state': this.state,
             'zip': this.zip,
             'id': this.id
            };
            console.log(param);
            axios.post('/contacts', param)
                .then(response =>  {
                    this.resetModal();
                    $('#exampleModal').modal('toggle');
                    window.contact_datatable.ajax.reload();
                })
                .catch(error => console.log(error));
        }
      },
    });

    $(document).ready(function(){
        $(document).on('click', '.EditContact', function(){
            var contact_id = $(this).data('id');
            app.editContact(contact_id);
        });
        $(document).on('click', '.deleteContact', function(){
            var contact_id = $(this).data('id');
            app.deleteContact(contact_id);
        });
        window.contact_datatable = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('datatable_contacts') }}',
            columns : [
                {data: 'name'},
                {data: 'email'},
                {data: 'phone'},
                {data: 'action'}
            ],
        });
    });
</script>
@endsection
