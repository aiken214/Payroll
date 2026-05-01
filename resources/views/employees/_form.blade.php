@php $e = $employee ?? null; @endphp

<h6 class="text-muted border-bottom pb-2 mb-3">Personal Information</h6>
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Employee Number <span class="text-danger">*</span></label>
        <input type="text" name="employee_number" class="form-control" value="{{ old('employee_number', $e?->employee_number) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">First Name <span class="text-danger">*</span></label>
        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $e?->first_name) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Last Name <span class="text-danger">*</span></label>
        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $e?->last_name) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $e?->email) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Date Hired</label>
        <input type="date" name="date_hired" class="form-control" value="{{ old('date_hired', $e?->date_hired?->format('Y-m-d')) }}">
    </div>
</div>

<h6 class="text-muted border-bottom pb-2 mb-3 mt-4">Employment Details</h6>
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Department <span class="text-danger">*</span></label>
        <select name="department_id" class="form-select" required>
            <option value="">Select...</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ old('department_id', $e?->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Position <span class="text-danger">*</span></label>
        <select name="position_id" class="form-select" required>
            <option value="">Select...</option>
            @foreach($positions as $pos)
                <option value="{{ $pos->id }}" {{ old('position_id', $e?->position_id) == $pos->id ? 'selected' : '' }}>{{ $pos->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Employment Status <span class="text-danger">*</span></label>
        <select name="employment_status" class="form-select" required>
            @foreach(['Permanent/Regular', 'Probationary', 'Contractual', 'Part-time'] as $status)
                <option value="{{ $status }}" {{ old('employment_status', $e?->employment_status) == $status ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
        </select>
    </div>
</div>

<h6 class="text-muted border-bottom pb-2 mb-3 mt-4">Compensation</h6>
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Monthly Salary <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="monthly_salary" class="form-control" value="{{ old('monthly_salary', $e?->monthly_salary) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Salary Type</label>
        <select name="salary_type" class="form-select">
            <option value="CASH" {{ old('salary_type', $e?->salary_type) == 'CASH' ? 'selected' : '' }}>CASH</option>
            <option value="ATM" {{ old('salary_type', $e?->salary_type) == 'ATM' ? 'selected' : '' }}>ATM</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">ATM Number</label>
        <input type="text" name="atm_number" class="form-control" value="{{ old('atm_number', $e?->atm_number) }}">
    </div>
</div>

<h6 class="text-muted border-bottom pb-2 mb-3 mt-4">Government IDs</h6>
<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label">SSS #</label>
        <input type="text" name="sss_number" class="form-control" value="{{ old('sss_number', $e?->sss_number) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">PhilHealth #</label>
        <input type="text" name="phic_number" class="form-control" value="{{ old('phic_number', $e?->phic_number) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Pag-IBIG #</label>
        <input type="text" name="hdmf_number" class="form-control" value="{{ old('hdmf_number', $e?->hdmf_number) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">TIN #</label>
        <input type="text" name="tin_number" class="form-control" value="{{ old('tin_number', $e?->tin_number) }}">
    </div>
</div>
