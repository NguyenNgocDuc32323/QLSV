<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24 mt-4">
        <h1 class="fw-semibold mb-0 body-title">Update User</h1>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('home') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <i class="fa-solid fa-house"></i>
                    Dashboard
                </a>
            </li>
        </ul>
    </div>
    <div class="row justify-content-center align-items-center user-manage-block">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile"
                                role="tab" aria-controls="profile" aria-selected="true">Profile</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="password-tab" data-bs-toggle="tab" href="#password" role="tab"
                                aria-controls="password" aria-selected="false">Change Password</a>
                        </li>
                    </ul>
                    <!-- Tab Content -->
                    <div class="tab-content" id="myTabContent">
                        <!-- Profile Tab -->
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <form action="{{route('user-profile-edit-post',$user->id)}}" method="POST" enctype="multipart/form-data" class="w-50 mx-auto">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{$user->username}}">
                                    @if ($errors->has('name'))
                                        <span class='text-danger-login'>{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}">
                                    @if ($errors->has('email'))
                                        <span class='text-danger-login'>{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{$user->phone}}">
                                    @if ($errors->has('phone'))
                                        <span class='text-danger-login'>{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-3">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" value="{{$user->address}}">
                                    @if ($errors->has('address'))
                                        <span class='text-danger-login'>{{ $errors->first('address') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-3">
                                    <div class="d-flex">
                                        <label for="avatar">Current Avatar</label>
                                        <img src="{{asset($user->avatar)}}" alt="" class="current-user-image">
                                    </div>
                                    <input type="file" class="form-control mt-3" id="avatar" name="avatar">
                                    @if ($errors->has('avatar'))
                                        <span class='text-danger-login'>{{ $errors->first('avatar') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn-success">Update Profile</button>
                                </div>
                            </form>
                        </div>
                        <!-- Password Tab -->
                        <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                            <form action="{{route('user-edit-password-post',$user->id)}}" method="POST" class="w-50 mx-auto">
                                @csrf
                                <div class="form-group mb-3 position-relative">
                                    <label for="current-password">Current Password</label>
                                    <input type="password" class="form-control" id="current-password" name="current-password">
                                    <span class="show-pass-word">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                    @if ($errors->has('current-password'))
                                        <span class="text-danger-login">{{ $errors->first('current-password') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-3 position-relative">
                                    <label for="new-password">New Password</label>
                                    <input type="password" class="form-control" id="new-password" name="new-password">
                                    <span class="show-pass-word">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                    @if ($errors->has('new-password'))
                                        <span class="text-danger-login">{{ $errors->first('new-password') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-3 position-relative">
                                    <label for="confirm-password">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm-password" name="new-password_confirmation">
                                    <span class="show-pass-word">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                    @if ($errors->has('new-password_confirmation'))
                                        <span class="text-danger-login">{{ $errors->first('new-password_confirmation') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn-success">Change Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>