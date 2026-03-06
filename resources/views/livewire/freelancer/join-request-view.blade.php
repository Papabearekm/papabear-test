<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 text-danger">Details of {{ $request->first_name . ' ' . @$request->last_name }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th>Cover</th>
                                <td><a target="_blank" href="{{ $request->cover ? Storage::disk('spaces')->url($request->cover) : 'javascript:void(0)'  }}">{{ $request->cover ? 'View Image' : 'No Image'  }}</a></td>
                            </tr>
                            <tr>
                                <th>First Name :</th>
                                <td>{{ $request->first_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Last Name :</th>
                                <td>{{ $request->last_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email :</th>
                                <td>{{ $request->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Country Code :</th>
                                <td>{{ $request->country_code ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Mobile :</th>
                                <td>{{ $request->mobile ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Gender :</th>
                                <td>{{ $request->gender == 0 ? 'Female' : ($request->gender == 1 ? 'Male' : '-') }}</td>
                            </tr>
                            <tr>
                                <th>Zip Code :</th>
                                <td>{{ $request->zipcode ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Categories :</th>
                                <td>
                                    @php
                                        $categories = json_decode($request->categories, true);
                                        $categoryNames = App\Models\Category::whereIn('id', $categories)->pluck('name')->toArray();
                                    @endphp
                                    @foreach ($categoryNames as $category)
                                        - {{ $category }} <br>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>Shop Name :</th>
                                <td>{{ $request->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Address :</th>
                                <td>{{ $request->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Latitude :</th>
                                <td>{{ $request->lat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Longitude :</th>
                                <td>{{ $request->lng ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>About :</th>
                                <td>{{ $request->about ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Fees Start :</th>
                                <td>{{ $request->fee_start ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>ID Proof :</th>
                                <td><a target="_blank" href="{{ $request->id_proof ? Storage::disk('spaces')->url($request->id_proof) : 'javascript:void(0)'  }}">{{ $request->id_proof ? 'View Image' : 'No Image'  }}</a></td>
                            </tr>
                            <tr>
                                <th>ID Proof Backside:</th>
                                <td><a target="_blank" href="{{ $request->id_proof_back ? Storage::disk('spaces')->url($request->id_proof_back) : 'javascript:void(0)'  }}">{{ $request->id_proof_back ? 'View Image' : 'No Image'  }}</a></td>
                            </tr>
                            <tr>
                                <th>Bank Name  :</th>
                                <td>{{ $request->bank_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Bank IFSC  :</th>
                                <td>{{ $request->bank_ifsc ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Bank Account Number  :</th>
                                <td>{{ $request->bank_account_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Bank Customer Name  :</th>
                                <td>{{ $request->bank_customer_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Whatsapp Number  :</th>
                                <td>{{ $request->whatsapp_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Team Size  :</th>
                                <td>{{ $request->team_size ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>PAN  :</th>
                                <td>{{ $request->pan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>GST  :</th>
                                <td>{{ $request->vat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Heard Us From  :</th>
                                <td>{{ $request->heard_us_from ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Executive  :</th>
                                @php
                                $executive = $request->executive_id ? \app\Models\User::find($request->executive_id) : null;
                                @endphp
                                <td>{{ $executive ? ($executive->first_name . ' ' . $executive->last_name) : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--end table-->
                </div>
            </div>
        </div>
    </div>
</div>
