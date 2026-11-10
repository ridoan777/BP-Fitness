<x-app-layout>
	@php
		$breadCrumbs = [
			['label' => 'Users', 'url' => 'backend_all_user', 'icon' => 'userTrippleLine'],
			['label' => 'Single User'],
		];
	 @endphp

	<x-indicators.breadcrumb :crumbs="$breadCrumbs" />

	<!-- Start Main Widgets -->
	<x-indicators.page_header_widget pageName="Single User Details" />
	<!-- End Main Widgets -->

	<!-- Announcement Banner -->
	<x-ui_items.banners.announcement_banner rolesToShow="super_admin"
		message="⚠️Be Careful! You are logged-in as Super Admin. Any changes you do, will have direct-immediate effect without verifications! Better log-in as an Admin." />
	<!-- End Announcement Banner -->

	<!-- Alert Component -->
	<x-indicators.alert-component message="Single User " />
	<!-- Alert Component -->

	@php
		$userAvatar = \App\Helpers\Ui\GetUrlSafeFiles::alignAvatar($user, false);
		$zones = \App\Helpers\Ui\ConvertDynamicTimezone::handle();
	 @endphp

	<!-- Dynamic Timezone -->
	@php
		$tz = config('settings.display_timezone');
		$timezoneOffset = Carbon\Carbon::now($tz)->format('P');
	 @endphp
	<!-- Dynamic Timezone -->

	<!-- -------------------------------- APP AREA Starts -------------------------------- -->

	<div class="p-6 space-y-6">

		<!------------ HEADER ------------>
		<div class="flex justify-end items-center gap-4 border-b pb-4">
			<a href="{{ route('backend_all_user') }}" class="darkButton">← Back</a>
			<form action="{{ route('backend_user_delete', $user->id) }}" method="POST" class="inline-block p-0 m-0">
				@csrf
				@method('DELETE')
				<button type="submit" class="deleteButton"
					onclick="return confirm('Are you sure you want to delete this user?')">
					{!! \App\Helpers\IconPack::trash(['class' => 'iconPackItem w-4 h-4 text-gray-100']) !!}
				</button>
			</form>
		</div>
		<!------------ HEADER ------------>

		<!------------ BASIC INFO ------------>
		<div class="bg-white dark:bg-neutral-700 shadow rounded-lg p-6 space-y-4">
			<div class="flex items-center space-x-4">
				<div>
					@if ($user->avatar)
						<img src="{{ $userAvatar }}" alt="Avatar" class="w-20 h-20 rounded-full border">
					@else
						<div class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center text-gray-500">
							N/A</div>
					@endif
				</div>
				<div>
					<h3 class="text-xl font-semibold">{{ $user->name }}</h3>
					<p class="text-sm text-gray-500 dark:text-gray-300">Role: <span
							class="font-medium">{{ ucfirst($user->user_role) }}</span></p>
					<p class="text-sm text-gray-500 dark:text-gray-300">
						Status:
						@if ($user->status)
							<span class="text-green-600 font-medium">Active</span>
						@else
							<span class="text-red-600 dark:text-red-400 font-medium">Inactive / Blacklisted</span>
						@endif
					</p>
				</div>
			</div>
		</div>
		<!------------ BASIC INFO ------------>

		<!------------ ACCOUNT INFORMATION ------------>
		<div class="bg-white dark:bg-neutral-700 shadow rounded-lg p-6">
			<h4 class="text-lg font-semibold mb-4">Account Information</h4>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

				<p class="dark:text-gray-300"><strong>DB ID:</strong> {{ $user?->id }}</p>
				<p class="dark:text-gray-300"><strong>UID:</strong> {{ $user?->user_uid }}
				<span class="grayLabel">(Unique identifier of this user entity. Immutable.)</span></p>
				<p class="dark:text-gray-300"><strong>Username:</strong> {{ $user?->username ?? null }}</p>
				<p class="dark:text-gray-300"><strong>Gender:</strong> {{ $user?->gender ?? "N/A" }}</p>
				<p class="dark:text-gray-300"><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
				<p class="dark:text-gray-300"><strong>Email:</strong> {{ $user->email }}</p>
				<p class="dark:text-gray-300"><strong>Address:</strong> {{ $user->address ?? 'N/A' }}</p>

				<p class="dark:text-gray-300"><strong>Registered At:</strong>
					{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->getRawOriginal('created_at'), 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') }}
					(UTC{{ $zones['offset'] }})

				</p>
				<p class="dark:text-gray-300"><strong>Last Updated:</strong>
					{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->getRawOriginal('updated_at'), 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') }}
					(UTC{{ $zones['offset'] }})
				</p>

				<p class="flex items-center gap-2 dark:text-gray-300">
					<strong>Email Verified:</strong>

					@if ($user->email_verified_at)
						{!! \App\Helpers\IconPack::singleTick(['class' => 'iconPackItem w-6 h-6 text-green-500']) !!}
						{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->getRawOriginal('email_verified_at'), 'UTC')->timezone($zones['zone'])->format('d M Y, H:i') }}
						(UTC{{ $zones['offset'] }})
					@else
						{!! \App\Helpers\IconPack::cross(['class' => 'iconPackItem w-6 h-6 text-red-500']) !!}
						<span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Not Verified</span>

						<!-- Manual Verify Toggle -->
					@endif
					<a href="{{ route('backend_user_manual_verify_store', $user->id) }}"
						class="manual-verify-toggle ml-3 relative inline-block w-11 h-6 cursor-pointer group">

						<!-- Hidden checkbox just for animation -->
						<input type="checkbox" class="sr-only peer" {{ $user->email_verified_at ? 'checked' : '' }}>

						<!-- Toggle background -->
						<span
							class="absolute inset-0 rounded-full transition 
							{{ $user->email_verified_at ? 'bg-green-500 peer-checked:bg-green-500' : 'bg-gray-300 peer-checked:bg-green-500' }}">
						</span>

						<!-- Toggle knob -->
						<span class="absolute top-1/2 left-0.5 -translate-y-1/2 w-5 h-5 bg-white rounded-full shadow-xs transition 
							{{ $user->email_verified_at ? 'translate-x-full' : '' }}">
						</span>
					</a>
					<span class="grayLabel">(manually grant or revoke verification)</span>

				</p>

			</div>
		</div>
		<!------------ ACCOUNT INFORMATION ------------>

		<!------------ UPDATE EMAIL ------------>
		@php
			$userVerifiaction = App\Models\Users\UserVerification::userId($user?->id)->type('email_change')->latest()->first();
			$isExpired = $userVerifiaction?->isExpired();
			$minutes = \Carbon\Carbon::parse(now())->diffInMinutes(\Carbon\Carbon::parse($userVerifiaction?->expires_at));
		@endphp

		@if ($userVerifiaction)
			<div class="p-6 bg-white dark:bg-neutral-700 shadow rounded-lg">
				<div class="flex items-center justify-between">
					<h4 class="text-lg font-semibold mb-4">Email Change Verification</h4>

					<div class="flex_between_center gap-4">
						<form id="resend-verification" method="post" action="{{ route('backend_verify_email_resent_otp') }}">
							@csrf
							<!--- IDENTIFIER HIDDEN INPUTS -->
							<input type="hidden" name="flag" class="smallInput" value="resend_verify_email" readonly>
							<input type="hidden" name="user_id" class="smallInput"
								value="{{ $userVerifiaction?->user_id ?? null }}" readonly>
							<!--- IDENTIFIER HIDDEN INPUTS -->
							<button form="resend-verification" class="darkButton">
								{{ __('Rsend OTP') }}
							</button>
						</form>
						<button type="button" data-modal-target="hs-delete-modal" data-modal-toggle="hs-delete-modal"
							data-delete-url="{{ route('backend_verify_email_delete', $userVerifiaction?->id) }}"
							class="deleteButton">
							{!! \App\Helpers\IconPack::trash(['class' => 'iconPackItem w-4 h-4 text-gray-100']) !!}
							Delete Attempt
						</button>
					</div>
				</div>
				<x-ui_items.modals.delete-modal id="hs-delete-modal" title="Confirm Deletion" confirmText="Yes, delete it"
					cancelText="Cancel" message="Are you sure you want to delete? This action cannot be undone" />

				<form method="POST" action="{{ route('backend_user_update_verify_email') }}" class="rounded-lg p-2 "
					enctype="multipart/form-data">
					@csrf
					<div class="mb-5 mt-2">
						<!--- IDENTIFIER HIDDEN INPUTS -->
						<input type="hidden" name="flag" class="smallInput" value="email_change" readonly>
						<input type="hidden" name="user_id" class="smallInput" value="{{ $userVerifiaction?->user_id ?? null }}"
							readonly>
						<!--- IDENTIFIER HIDDEN INPUTS -->
						<label for="name" class="inputLabelMedium">OTP
							<span class="text-red-500">*</span>
							<span class="text-sm text-gray-400 font-light">(Check the old email for OTP. Admins need OTP
								to change other admins' email. Login as Super Admin to avoid verification!)</span>
							<span class="text-sm text-blue-400 font-light">Current OTP expires/expired in:
								{{ round($minutes, 0) }} minutes</span>
						</label>
						<input type="text" name="otp" class="normalInput">

						<button type="submit" class="my-4 showButton">Verify & Change</button>
					</div>
				</form>
			</div>
		@endif

		<!------------ UPDATE EMAIL ------------>

		<!------------ UPDATE USER ------------>
		<div class="p-6 space-y-6 bg-white dark:bg-neutral-700 shadow rounded-lg">
			<h4 class="text-lg font-semibold mb-4">Update User
				<span class="grayLabel">(The admins can modify non-admin user details)</span>
			</h4>
			<form method="POST" action="{{ route('backend_create_users_new_store') }}" class="rounded-lg p-2 "
				enctype="multipart/form-data">
				@csrf

				<!--- IDENTIFIER HIDDEN INPUTS -->
				<input type="hidden" name="flag" class="smallInput" value="update" readonly>
				<input type="hidden" name="id" class="smallInput" value="{{ $user?->id ?? null }}" readonly>
				<!--- IDENTIFIER HIDDEN INPUTS -->

				<div>
					<div class="grid grid-cols-2 gap-4">

						<div class="mb-5 mt-2">
							<label for="name" class="inputLabelMedium">User's Name
								<span class="text-red-500">*</span>
							</label>
							<input type="text" name="name" class="normalInput" value="{{ $user?->name ?? null }}">
						</div>

						<div>
							<label for="user_role" class="inputLabelMedium">Roles</label>
							<select name="user_role" class="dropdownSelectInputs">
								<option>--Select Role--</option>
								@foreach ($roles as $role)
									<option value="{{ $role->role_key }}" {{  $user->hasRoleKey($role?->role_key) ? 'selected' : '' }}>
										id:{{ $role?->id }} | Role name = {{ strtoupper($role?->name) }} | (Key:
										{{ $role?->role_key }})
									</option>
								@endforeach
							</select>
							@error('roles')
								<div>{{ $message }}</div>
							@enderror
						</div>

					</div>

				</div>
				<!-- -->
				<div class="grid grid-cols-2 gap-4">

					<div class="mb-5 mt-2">
						<label for="username" class="inputLabelMedium">Username
							<span class="text-red-500">*</span>
							<span class="text-sm text-gray-400 font-light">(Must be unique)</span>
						</label>
						<input type="text" name="username" class="normalInput" value="{{ $user?->username ?? null }}">
					</div>

					<div class="mb-5 mt-2">
						<label for="email" class="inputLabelMedium">Email
							<span class="text-red-500">*</span>
							<span class="text-sm text-gray-400 font-light">(Must be unique)</span>
							<span class="text-sm text-gray-400 font-light"> (Admin added users will not go
								through verifications)</span>
						</label>
						<input type="text" name="email" class="normalInput" value="{{ $user?->email ?? null }}">
					</div>

				</div>
				<!-- -->
				<div class="grid grid-cols-2 gap-4">

					<div class="mb-5 mt-2">
						<label for="gender" class="inputLabelMedium">Gender
							<span class="text-sm text-gray-400 font-light">(Optional)</span>
						</label>
						<select name="gender" class="dropdownSelectInputs">
							<option value="" disabled @selected(empty($user?->gender))>
								-- Select Gender --
							</option>

							<option value="male" @selected($user?->gender === 'male')>Male</option>
							<option value="female" @selected($user?->gender === 'female')>Female</option>
							<option value="prefer_not" @selected($user?->gender === 'prefer_not')>
								Prefer not to say
							</option>
						</select>
					</div>

					<div class="mb-5 mt-2">
						<label for="phone" class="inputLabelMedium">Phone
							<span class="text-sm text-gray-400 font-light">(Optional)</span>
						</label>
						<input type="text" name="phone" class="normalInput" value="{{ $user?->phone ?? null }}">
					</div>

				</div>
				<!-- -->

				<div class="grid grid-cols-2 gap-4">

					<div class="mb-5 mt-2">
						<label for="password" class="inputLabelMedium">Password
							<span class="text-red-500">*</span>
							<span class="text-sm text-gray-400 font-light">(At least 8 characters)</span>
						</label>
						<input type="text" name="password" class="normalInput" placeholder="type new password">
					</div>

					<div class="mb-5 mt-2">
						<label for="password_confirmation" class="inputLabelMedium">Confirm Password
						</label>
						<input type=" text" name="password_confirmation" class="normalInput">
					</div>

				</div>
				<!-- -->

				<div class="mb-5">
					<label for="avatar" class="inputLabelMedium">User Avatar
						<span class="text-sm text-gray-400 font-light"> (Optional)</span>
						<span class="text-sm text-gray-400 font-light"> (Max 10 MB)</span>
					</label>

					<input type="file" id="avatarDropify" name="avatar" class="dropify" data-height="150" @if ($user?->avatar) data-default-file="{{ $userAvatar }}" @endif />

					<input type="hidden" name="remove_image" id="remove_avatar" value="0">
				</div>
		</div>

		<button type="submit" class="showButton my-4">Update User</button>
		</form>
		<!------------ UPDATE USER ------------>
	</div>

	<!------------ SETTINGS PREFERENCE ------------>
	<div class="bg-white dark:bg-neutral-700 shadow rounded-lg p-6">
		<h4 class="text-lg font-semibold mb-4">Settings Preference
			<span class="grayLabel">(User's choice. Not all options might function)</span>
		</h4>

		<table class="w-full">
			<thead>
				<tr>
					<th class="dark:text-gray-200">In-App Notification</th>
					<th class="dark:text-gray-200">Email Notification</th>
					<th class="dark:text-gray-200">Push Notification</th>
					<th class="dark:text-gray-200">Activity Log</th>
					<th class="dark:text-gray-200">Data Status</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="{{ $settingsPreference?->in_app_notification ? 'text-green-600' : 'text-red-600' }}">
						{{ $settingsPreference?->in_app_notification ? 'ON' : 'NO' }}
					</td>
					<td class="{{ $settingsPreference?->email_notification ? 'text-green-600' : 'text-red-600' }}">
						{{ $settingsPreference?->email_notification ? 'ON' : 'NO' }}
					</td>
					<td class="{{ $settingsPreference?->push_notification ? 'text-green-600' : 'text-red-600' }}">
						{{ $settingsPreference?->push_notification ? 'ON' : 'NO' }}
					</td>
					<td class="{{ $settingsPreference?->activity_log ? 'text-green-600' : 'text-red-600' }}">
						{{ $settingsPreference?->activity_log ? 'ON' : 'NO' }}
					</td>
					<td class="{{ $settingsPreference?->status ? 'text-green-600' : 'text-red-600' }}">
						{{ $settingsPreference?->status ? 'ON' : 'NO' }}
					</td>
				</tr>
			</tbody>
		</table>

	</div>
	<!------------ SETTINGS PREFERENCE ------------>

	<!------------ USER ACCESS / BANS ------------>
	<div class="bg-white dark:bg-neutral-700 shadow rounded-lg p-6">
		<h4 class="text-lg font-semibold mb-4">User Access & Restrictions
			<span class="grayLabel">(Penalties to the user by the admin(s))</span>
		</h4>

		<table class="w-full user-details-table">
			<thead>
				<tr>
					<th class="dark:text-gray-200">Chat Ban</th>
					<th class="dark:text-gray-200">Gallery Ban</th>
					<th class="dark:text-gray-200">Store Ban</th>
					<th class="dark:text-gray-200">View Ban</th>
					<th class="dark:text-gray-200">Ban Expiry</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					@foreach(['chat_ban', 'gallery_ban', 'store_ban', 'view_ban'] as $banType)
						<td class="p-2">
							<form action="{{ route('backend_user_access', $user->id) }}" method="POST"
								class="flex flex-col items-center gap-2">
								@csrf
								{{-- <input type="hidden" name="{{$banType	}}" value="{{ $banType }}"> --}}
								<input type="hidden" name="{{ $banType }}" value="{{ $userAccess?->$banType ? 0 : 1 }}">
								<input type="hidden" name="status" value="{{ $userAccess?->$banType ? 0 : 1 }}">

								<span class="text-xs font-bold {{ $userAccess?->$banType ? 'text-red-600' : 'text-green-600' }}">
									{{ $userAccess?->$banType ? 'BANNED' : 'ALLOWED' }}
								</span>

								<button type="submit" class="relative inline-flex items-center cursor-pointer">
									<div
										class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 transition-colors {{ $userAccess?->$banType ? 'bg-red-500' : 'bg-gray-300' }}">
										<div
											class="absolute top-0.5 left-0.5 bg-white rounded-full h-5 w-5 transition-all {{ $userAccess?->$banType ? 'translate-x-full border-white' : '' }}">
										</div>
									</div>
								</button>
							</form>
						</td>
					@endforeach
					<td class="dark:text-gray-300 p-2">
						<form action="{{ route('backend_user_access', $user->id) }}" method="POST"
							class="flex flex-col gap-2">
							@csrf
							<input type="hidden" name="update_expiry" value="1">
							<input type="date" name="ban_expiry" class="text-xs p-1 rounded border border-gray-300 dark:bg-neutral-800"
								value="{{ $userAccess?->ban_expiry ? \Carbon\Carbon::parse($userAccess->ban_expiry)->format('Y-m-d') : '' }}">
								<div>
									<button type="submit" class="showButton">Update</button>
								</div>
						</form>
						<div class="mt-1 text-[10px]">
							@if($userAccess?->ban_expiry)
								Current ban expires: {{ \Carbon\Carbon::parse($userAccess->ban_expiry)->format('d M Y H:i A') }}
							@else
								N/A
							@endif
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="5" class="p-4">
						<form action="{{ route('backend_user_access', $user->id) }}" method="POST" class="space-y-3">
							@csrf

							<label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Update Admin Note</label>

							<div class="flex gap-3 items-center">
								<div class="flex-1">
									<textarea name="admin_note"
										class="summernote w-full min-h-35 rounded-md border border-gray-300 dark:border-neutral-600 p-2">{!! $userAccess?->admin_note !!}</textarea>
								</div>

								<button type="submit" class="showButton">Save</button>
							</div>
						</form>
					</td>
				</tr>
			</tbody>
		</table>

		@if($userAccess?->admin_note)
			<div class="mt-4 p-4 bg-gray-50 dark:bg-neutral-800 rounded border border-gray-200 dark:border-neutral-600">
				<h5 class="text-sm font-semibold mb-1 dark:text-gray-200">Admin Note:</h5>
				<div class="text-sm text-gray-600 dark:text-gray-400">{!! $userAccess->admin_note !!}</div>
			</div>
		@endif
	</div>
	<!------------ USER ACCESS / BANS ------------>

	<!------------ VERIFICATION INFO ------------>
	<div class="bg-white dark:bg-neutral-700 shadow rounded-lg p-6">
		<h4 class="text-lg font-semibold mb-4">Verification Details
			<span class="grayLabel">(When user tries to verify the credential or send OTP)</span>
		</h4>
		@if ($verification)
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<p class="dark:text-gray-200"><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $verification->type)) }}
				</p>
				@php
					$displayOTP = "You don't have permissions to view the OTP";
					$currentUser = auth()->user();

					if ($currentUser->hasRoleKey('super_admin') || !($user->hasRoleKey('admin', 'super_admin') && $currentUser->id !== $user->id)) {
						$displayOTP = $verification->otp;
					}
				@endphp
				<p class="dark:text-gray-200"><strong>OTP:</strong> {{ $displayOTP }}</p>

				<p class="dark:text-gray-200"> <strong>Expires At:</strong>
					{{ Carbon\Carbon::parse($verification->expires_at)->timezone($tz)->format('d M Y, H:i') }}
					UTC{{ $timezoneOffset }}
					{{ $verification?->expiry_duration ? "(" . $verification?->expiry_duration . " mins)" : null }}
				</p>

				<p class="dark:text-gray-200">
					<strong>Created At:</strong>
					{{ Carbon\Carbon::parse($verification->created_at)->timezone($tz)->format('d M Y, H:i') }}
					UTC{{ $timezoneOffset }}
				</p>

				<p class="dark:text-gray-200">
					<strong>OTP Status:</strong>
					@if (\Carbon\Carbon::now()->gt($verification->expires_at))
						<span class="text-red-600 font-medium">Expired</span>
					@else
						<span class="text-green-600 font-medium">Valid</span>
					@endif
				</p>

				<p class="dark:text-gray-200">
					<strong>New Value:</strong> {{ $verification?->new_value ?? null }}
				</p>
			</div>
		@else
			<p class="text-gray-500">No verification record found for this user. Or the user might already be
				verified.
			</p>
		@endif
	</div>
	<!------------ VERIFICATION INFO ------------>

	</div>
	<!-- -------------------------------- APP AREA Starts -------------------------------- -->

	<style scoped>
		table tr th,
		table tr td {
			text-align: center;
			border: 1px solid #bbbfc7;
		}
	</style>
</x-app-layout>