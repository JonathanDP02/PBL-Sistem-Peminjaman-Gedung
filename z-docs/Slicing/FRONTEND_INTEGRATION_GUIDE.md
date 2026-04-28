# Frontend Integration Guide - Space.in Mock API
**For: Jo (Frontend Developer)**  
**Date:** April 28, 2026  
**Purpose:** Enable frontend development without waiting for backend implementation  

---

## 📌 Quick Start

### Step 1: Import Postman Collection
1. Download [POSTMAN_COLLECTION.json](./POSTMAN_COLLECTION.json)
2. Open Postman → File → Import
3. Select the JSON file → Done ✓
4. In environment variables, set:
   - `base_url` = `http://localhost:8000`
   - `token` = Your JWT token from login response

### Step 2: Set Up Environment Variables
You'll see this in Postman after import:
```json
{
  "base_url": "http://localhost:8000",
  "token": "YOUR_JWT_TOKEN_HERE"
}
```

**To get a valid token:**
1. Run POST login request (use test credentials below)
2. Copy the `token` value from response
3. Paste into Postman environment variable

**Test Credentials:**
```
Email: kajur.ti@spacein.test
Password: 12345
Role: Approver
```

---

## 📚 Key API Endpoints for Frontend

### 1. **Approver Dashboard (Meja Kerja)** ⭐ MAIN SCREEN
```
GET /api/approvals/pending
```

**What Jo Needs to Build:**
- [ ] Pending approvals list with cards/table
- [ ] Click to open approval detail modal
- [ ] Priority indicator (high/normal/urgent)
- [ ] Time remaining badge
- [ ] Refresh pending count in real-time

**Mock Response Sample:**
```json
{
  "success": true,
  "count": 3,
  "data": [
    {
      "id": 1,
      "booking": {
        "id": 5,
        "event_name": "Seminar Cybersecurity 2026",
        "booking_date": "2026-05-15",
        "start_time": "09:00",
        "end_time": "12:00",
        "status": "Pending",
        "current_step": 2,
        "created_at": "2026-04-28T10:30:00Z"
      },
      "room": {
        "room_name": "Auditorium Utama",
        "capacity": 500
      },
      "peminjam": {
        "name": "Rini Kusuma",
        "unit": { "name": "HMTI" }
      },
      "priority_indicator": "high",
      "time_remaining": "17 days"
    }
  ]
}
```

**CSS Layout Hints:**
- Card grid (2-3 columns on desktop)
- Event name + date/time prominent
- Room info + capacity
- Approver position badge ("Ketua Jurusan")
- Status color coded:
  - 🔴 `urgent` = Red with alert icon
  - 🟡 `high` = Orange
  - 🟢 `normal` = Green/gray

---

### 2. **Approval Detail & Review**
```
GET /api/approvals/{booking_id}
POST /api/approvals/{booking_id}/approve
POST /api/approvals/{booking_id}/reject
```

**What Jo Needs to Build:**
- [ ] Detail modal/page with booking info
- [ ] Display approval timeline (previous approvals)
- [ ] Show all uploaded documents with download links
- [ ] Approval/Rejection form with notes textarea
- [ ] "Approve" button (conditional: show if no attachment required OR attachment uploaded)
- [ ] "Reject" button with notes validation (required)

**Sample Response for Detail:**
```json
{
  "booking": {
    "event_name": "Seminar Cybersecurity 2026",
    "room": { "room_name": "Auditorium Utama", "capacity": 500 },
    "peminjam": { "name": "Rini Kusuma", "email": "rini@..." },
    "approval_timeline": [
      {
        "step_order": 1,
        "position": "Kaprodi",
        "approver_name": "Dr. Ahmad Syaiful Rohman",
        "approval_status": "Approved",
        "approved_at": "2026-04-28T08:15:00Z",
        "notes": "Disetujui. Sudah sesuai dengan SOP."
      },
      {
        "step_order": 2,
        "position": "Ketua Jurusan",
        "approval_status": "Pending",
        "requires_attachment": true
      }
    ],
    "documents": [
      {
        "document_name": "Proposal Acara",
        "file_url": "storage/bookings/5/proposal_seminar.pdf",
        "uploaded_at": "2026-04-28T10:35:00Z"
      }
    ]
  }
}
```

**Button Logic:**
```javascript
// Approve button state
if (currentStep.requires_attachment && !attachmentUploaded) {
  approveButton.disabled = true; // Locked
  approveButton.text = "Upload lampiran untuk lanjut ▲";
} else {
  approveButton.disabled = false;
}

// Reject button state
if (rejectionNotes.value.trim() === '') {
  rejectButton.disabled = true;
}
```

---

### 3. **Room Search (for Peminjam)**
```
GET /api/rooms/available?date=2026-05-20&start=09:00&end=12:00
```

**Mock Data Pattern:**
```json
{
  "count": 4,
  "available_rooms": [
    {
      "id": 3,
      "room_name": "Auditorium Utama",
      "capacity": 500,
      "building": { "building_name": "Gedung Rektorat" },
      "facilities": ["Projector 4K", "Sound System", "AC", "WiFi"]
    }
  ]
}
```

**Format Tips:**
- Sort by capacity (small → large)
- Show building name clearly
- List facilities as badges/tags

---

### 4. **Booking Timeline**
```
GET /api/bookings/{id}/approvals
```

**Sample Response:**
```json
{
  "timeline": [
    {
      "step_order": 1,
      "position": "Kaprodi",
      "approver_name": "Dr. Ahmad Syaiful Rohman",
      "approval_status": "Approved",
      "approved_at": "2026-04-28T08:15:00Z"
    },
    {
      "step_order": 2,
      "position": "Ketua Jurusan",
      "approval_status": "Pending"
    }
  ]
}
```

**Design Pattern:**
- Vertical timeline with steps
- Green ✓ for Approved
- Yellow ⏳ for Pending
- Red ✗ for Rejected
- Show approver name + position  
- Show approval date/time
- Show notes if any

---

## 🎨 Mock Response Structure You'll Use

### Common Data Patterns

**Booking Object:**
```json
{
  "id": 5,
  "event_name": "string",
  "event_description": "string",
  "booking_date": "Y-m-d",
  "start_time": "H:i",
  "end_time": "H:i",
  "status": "Draft|Pending|Revising|Approved|Rejected",
  "current_step": 1,
  "revision_count": 0
}
```

**Room Object:**
```json
{
  "id": 3,
  "room_name": "string",
  "room_code": "CODE",
  "capacity": 500,
  "floor": 1,
  "building": { "id": 1, "building_name": "string" },
  "facilities": ["Projector", "WiFi"]
}
```

**User/Peminjam Object:**
```json
{
  "id": 8,
  "name": "string",
  "nim": "2141720123",
  "email": "string",
  "phone": "string",
  "unit": { "id": 3, "name": "HMTI" }
}
```

**Approval Step Object:**
```json
{
  "step_order": 1,
  "position": "Kaprodi|Ketua Jurusan|Wakil Direktur",
  "approver_name": "string",
  "approval_status": "Approved|Pending|Rejected",
  "notes": "string|null",
  "requires_attachment": true|false,
  "approved_at": "ISO datetime|null"
}
```

---

## 🔧 Testing Checklist for Jo

- [ ] Login works → Get JWT token
- [ ] Pending approvals list loads
- [ ] Card layout responsive (desktop/tablet/mobile)
- [ ] Click card opens detail modal
- [ ] Timeline displays correctly
- [ ] Document links work (mock PDFs)
- [ ] Approve form shows and validates notes
- [ ] Reject form shows and validates notes
- [ ] Button states change based on current_step & requires_attachment

---

## 💾 Variable Mapping Reference

**These JSON path names MUST match in your frontend code:**

| Field | Path | Type | Example |
|-------|------|------|---------|
| Booking ID | `booking.id` | number | 5 |
| Event Name | `booking.event_name` | string | "Seminar Cybersecurity 2026" |
| Date | `booking.booking_date` | Y-m-d | "2026-05-15" |
| Room Name | `room.room_name` | string | "Auditorium Utama" |
| Capacity | `room.capacity` | number | 500 |
| Peminjam Name | `peminjam.name` | string | "Rini Kusuma" |
| Unit | `peminjam.unit.name` | string | "HMTI" |
| Status | `booking.status` | enum | "Pending\|Approved\|Rejected" |
| Current Step | `booking.current_step` | number | 1, 2, 3 |
| Priority | `priority_indicator` | string | "high\|normal\|urgent" |
| Time Remaining | `time_remaining` | string | "17 days" |

---

## 🚀 How to Test Without Backend

### Option 1: Mock API Server (Recommended)
Use [Mock Server](https://mockoon.com/) or [json-server](https://github.com/typicode/json-server):

```bash
# Install json-server globally
npm install -g json-server

# Create db.json with mock data
# Run mock server
json-server --watch db.json --port 3001

# In your frontend code:
// Change API_URL to http://localhost:3001
```

### Option 2: Hardcode Mock Data
```javascript
// src/composables/useApprovals.js
export const usePendingApprovals = () => {
  // Hardcoded mock data while backend is being built
  const approvals = ref([
    {
      id: 1,
      booking: {
        id: 5,
        event_name: "Seminar Cybersecurity 2026",
        // ... all fields from API_DOCUMENTATION.md
      }
      // ...
    }
  ]);
  
  return { approvals };
};
```

### Option 3: Use Postman Interceptor
- In Postman → Send request → Get response
- Copy response → Mock in frontend until backend ready

---

## 📋 Screen Checklist for Jo

### Approver Dashboard (Dasbor Pejabat)
**File:** `resources/views/user/approver/meja-kerja.blade.php`

Components needed:
```
┌─ Navbar with logout
├─ Sidebar (Meja Kerja | History | Settings)
├─ Main Content:
│  ├─ Filter bar (All | Urgent | Today)
│  ├─ Sort dropdown (-created_at | created_at | event_name)
│  ├─ Pending Count Badge
│  └─ Approval Cards Grid
│     ├─ Event Name (Large)
│     ├─ Room + Capacity
│     ├─ Date/Time
│     ├─ Priority Badge (High/Normal/Urgent)
│     ├─ Peminjam Name
│     ├─ Unit Name
│     ├─ Time Remaining
│     └─ [View] Button
└─ Detail Modal (opens when card clicked)
   ├─ Full booking info
   ├─ Timeline
   ├─ Documents
   ├─ Approval Form
   └─ Action Buttons
```

---

## ⚠️ IMPORTANT: Variable Naming Convention

**❌ DO NOT CHANGE** the JSON keys from the mock API!

❌ **Bad:**
```javascript
// This will break when backend is integrated
booking.namaAcara  // Wrong - API sends event_name
booking.ruangan    // Wrong - API sends room
```

✅ **Good:**
```javascript
// Match the API response exactly
booking.event_name
booking.room
booking.peminjam
booking.current_step
```

This is critical because when the backend is ready, these keys must match exactly or Jo's code will break.

---

## 📞 When Backend is Ready

1. Backend team deploys API endpoints
2. Jo changes `base_url` in environment from mock to real domain
3. Everything works automatically because variable names already match!

**Example:**
```javascript
// Before (Frontend dev phase)
const apiUrl = 'http://localhost:3001'; // Mock server

// After (Backend ready)
const apiUrl = 'https://api.spacein.test'; // Real backend
// No code changes needed - same variable names!
```

---

## 🎯 CRITICAL SUCCESS FACTORS

1. ✅ Use exact JSON keys from mock response
2. ✅ Test component with Postman responses
3. ✅ Build reusable card layouts (approval cards are same structure)
4. ✅ Mock all API calls in composables/services
5. ✅ Document any custom fields you add (must get backend approval)
6. ✅ Version lock the mock API schema (don't change field names)

---

## Next Steps

1. Jo opens API_DOCUMENTATION.md for detailed specs
2. Jo imports POSTMAN_COLLECTION.json into Postman
3. Jo creates mock data store (Pinia/Vuex) with response structure
4. Jo builds components with hardcoded mock data
5. Jo tests with real Postman responses
6. Backend team integrates - Jo just changes API URL!

---

**Created:** April 28, 2026  
**For:** Jo (Frontend)  
**Status:** Ready for Development

