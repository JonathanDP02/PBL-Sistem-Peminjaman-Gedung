# Space.in API - Quick Reference Card
**For:** Jo (Frontend Developer)  
**Print This!** 📄  

---

## 🔐 Authentication

| Endpoint | Method | Body |
|----------|--------|------|
| `/login` | POST | `{ email, password }` |

**Response:** Returns `{ user, token }`

**Test Credentials:**
```
Email: kajur.ti@spacein.test
Password: 12345
```

---

## 📊 Approver Dashboard (MAIN FEATURE)

### Fetch Pending Approvals
```
GET /api/approvals/pending?sort=-created_at&filter=all
Header: Authorization: Bearer {token}
```

**Returns:** Array of pending approvals with:
- `booking` (event name, date, time, status, current_step)
- `room` (room name, capacity, building)
- `peminjam` (name, email, unit)
- `current_approver_required` (position, step_order, requires_attachment)
- `priority_indicator` (high|normal|urgent)
- `time_remaining` (string)

**Count:** `response.count` (number of pending items)

---

### View Approval Detail
```
GET /api/approvals/{booking_id}
Header: Authorization: Bearer {token}
```

**Returns:** Single booking with:
- Approval timeline (all previous approvals)
- Document list (with file URLs)
- Current step requirements
- Peminjam contact info

---

### Approve Booking
```
POST /api/approvals/{booking_id}/approve
Header: Authorization: Bearer {token}
Body: { notes: "string" }

[Optional if requires_attachment = true]
Body: FormData with file
```

**Check Before Allowing:**
- If `current_step.requires_attachment = true` → User must upload file first

---

### Reject Booking
```
POST /api/approvals/{booking_id}/reject
Header: Authorization: Bearer {token}
Body: { notes: "required string" }
```

**Validation:**
- Notes cannot be empty
- Returns updated booking with `status: "Revising"`

---

## 🏢 Room Management

### Find Available Rooms
```
GET /api/rooms/available?date=Y-m-d&start=H:i&end=H:i
Header: Authorization: Bearer {token}
```

**Example:**
```
GET /api/rooms/available?date=2026-05-20&start=09:00&end=12:00
```

**Returns:** Array of rooms with:
- `room_name`, `room_code`, `capacity`, `floor`
- `building` (name, address)
- `facilities` (array of strings)
- `booking_workflow` (workflow name, total steps)

---

## 📋 Workflows & Requirements

### Get Workflow Requirements
```
GET /api/workflows/{workflow_id}/requirements
Header: Authorization: Bearer {token}
```

**Returns:** Array of required documents:
- `document_name`
- `is_mandatory` (boolean)
- `max_file_size` (string)
- `allowed_formats` (array)

---

## 📅 Bookings

### Fetch Booking Detail
```
GET /api/bookings/{booking_id}
Header: Authorization: Bearer {token}
```

**Returns:** Booking with workflow, room, user, documents

---

### Fetch Approval Timeline
```
GET /api/bookings/{booking_id}/approvals
Header: Authorization: Bearer {token}
```

**Returns:** Array of approval steps with:
- `step_order`
- `position` (job title)
- `approver_name`
- `approval_status` (Approved|Pending|Rejected)
- `notes`
- `approved_at` (ISO datetime)

---

## 🎨 Common Response Patterns

### Success Response
```json
{
  "success": true,
  "data": { /* actual data */ },
  "message": "optional message"
}
```

### Error Response
```json
{
  "success": false,
  "message": "error description",
  "errors": { /* field errors */ }
}
```

### Paginated Response
```json
{
  "success": true,
  "count": 10,
  "data": [ /* array of items */ ]
}
```

---

## 🔄 Status Flow

### Booking Status Values
```
Draft          → User filling form (Soft-Lock)
Pending        → Waiting for approvals
Revising       → Rejected, user revising
Approved       → All steps approved (Hard-Lock)
Rejected       → Final rejection
```

### Approval Status Values
```
Pending        → Waiting for this approver
Approved       → Approver approved
Rejected       → Approver rejected
```

---

## 📞 Key Variable Names (MUST MATCH!)

| What | Path |
|------|------|
| Event name | `booking.event_name` |
| Booking date | `booking.booking_date` (Y-m-d) |
| Start time | `booking.start_time` (H:i) |
| End time | `booking.end_time` (H:i) |
| Status | `booking.status` |
| Current step | `booking.current_step` |
| Room name | `room.room_name` |
| Room capacity | `room.capacity` |
| Building | `building.building_name` |
| Facilities | `room.facilities` (array) |
| Peminjam name | `peminjam.name` |
| Peminjam email | `peminjam.email` |
| Peminjam unit | `peminjam.unit.name` |
| Position required | `current_approver_required.position.name` |
| Requires file | `current_approver_required.requires_attachment` (boolean) |
| Priority | `priority_indicator` |
| Time left | `time_remaining` |
| Approver name | `approver_name` |
| Approval date | `approved_at` (ISO datetime) |
| Rejection reason | `notes` |

---

## ⚠️ Important Validations

### Before Approve
- [ ] Check `current_step.requires_attachment`
- [ ] If true → Get file from user before enabling button
- [ ] Validate file is PDF/DOC/DOCX

### Before Reject
- [ ] Check `notes` is not empty
- [ ] Minimum 10 characters recommended
- [ ] No special characters breaks (sanitize input)

### Before Book Room
- [ ] Check room is in available list
- [ ] Check date is not in the past
- [ ] Check end_time > start_time

---

## 🧪 Test with Mock Data

**Postman Collection Path:**
```
z-docs/POSTMAN_COLLECTION.json
```

1. Import into Postman
2. Set environment:
   - `base_url` = `http://localhost:8000`
   - `token` = From login response
3. Click "Send" on any endpoint
4. Copy response → Use in frontend

---

## 🚀 Integration Phases

| Phase | Action | API URL |
|-------|--------|---------|
| 1: Frontend Dev | Build UI with hardcoded mock data | Local mock server / hardcoded |
| 2: Testing | Test with Postman responses | `http://localhost:8000` (if running) |
| 3: Integration | Connect real backend | `https://api.spacein.test` |

---

## 📌 Debug Tips

### Check Response Structure
```javascript
console.log(JSON.stringify(response, null, 2));
// Paste output here ↓
```

### Missing Data?
1. Check `response.success === true`
2. Check `response.data` vs `response`
3. Check array `.length > 0`

### API Error?
| Status | Meaning |
|--------|---------|
| 401 | Invalid token - re-login |
| 403 | Not authorized (wrong role) |
| 404 | Resource not found |
| 422 | Validation error - check `response.errors` |
| 500 | Server error - contact backend team |

---

## 🎯 Most Used Endpoints (Priority Order)

1. **GET /api/approvals/pending** ← START HERE
2. **GET /api/approvals/{id}** ← Detail view
3. **POST /api/approvals/{id}/approve** ← Core action
4. **POST /api/approvals/{id}/reject** ← Core action
5. **GET /api/rooms/available** ← Search
6. **GET /api/workflows/{id}/requirements** ← Form requirements

---

## 💾 Save This For Reference

**All Documentation:**
- Full Specs: `z-docs/API_DOCUMENTATION.md`
- Postman: `z-docs/POSTMAN_COLLECTION.json`
- Integration: `z-docs/FRONTEND_INTEGRATION_GUIDE.md`
- This Card: `z-docs/API_QUICK_REFERENCE.md`

---

**Last Updated:** April 28, 2026  
**Status:** ✅ Ready for Development

