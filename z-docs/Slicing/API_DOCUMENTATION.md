# Space.in API Documentation
**Version:** 1.0  
**Last Updated:** April 28, 2026  
**Status:** Beta (Mock Responses for Frontend Development)

---

## 📋 Table of Contents
1. [Authentication](#authentication)
2. [Approver Dashboard (Meja Kerja)](#approver-dashboard---dasbor-pejabat)
3. [Room Management](#room-management)
4. [Booking Management](#booking-management)
5. [Workflow Management](#workflow-management)
6. [Approval Management](#approval-management)
7. [Timeline & History](#timeline--history)

---

## Authentication

### POST /login
Authenticates user and returns JWT token.

**Request:**
```json
{
  "email": "kajur.ti@spacein.test",
  "password": "12345"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "user": {
    "id": 3,
    "name": "Dr. Bambang Suryanto",
    "email": "kajur.ti@spacein.test",
    "role": "Approver",
    "unit": {
      "id": 2,
      "name": "Jurusan Teknik Informatika",
      "level": "Jurusan"
    },
    "position": {
      "id": 1,
      "name": "Ketua Jurusan"
    },
    "avatar_url": "https://api.spacein.test/storage/avatars/user3.jpg"
  },
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

**Error (401 Unauthorized):**
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

---

## Approver Dashboard - Dasbor Pejabat

### GET /api/approvals/pending
Fetch all pending approvals for current approver (Meja Kerja / Work Desk).

**Query Parameters:**
- `sort` (optional): `created_at|-created_at|status|event_name` (default: `-created_at`)
- `filter` (optional): `all|urgent|today` (default: `all`)

**Request:**
```
GET /api/approvals/pending?sort=-created_at&filter=urgent
Authorization: Bearer {token}
```

**Response (200 OK):**
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
        "event_description": "Seminar internasional menghadirkan pembicara dari Google & Microsoft",
        "booking_date": "2026-05-15",
        "start_time": "09:00",
        "end_time": "12:00",
        "status": "Pending",
        "current_step": 2,
        "revision_count": 0,
        "created_at": "2026-04-28T10:30:00Z"
      },
      "room": {
        "id": 3,
        "room_name": "Auditorium Utama",
        "room_code": "AUD-001",
        "capacity": 500,
        "building": {
          "id": 1,
          "building_name": "Gedung Rektorat",
          "building_code": "GR"
        }
      },
      "peminjam": {
        "id": 8,
        "name": "Rini Kusuma",
        "nim": "2141720123",
        "email": "rini.kusuma@student.polinema.ac.id",
        "unit": {
          "id": 3,
          "name": "HMTI (Himpunan Mahasiswa Teknik Informatika)"
        }
      },
      "workflow": {
        "id": 2,
        "name": "Peminjaman Auditorium",
        "total_steps": 2
      },
      "current_approver_required": {
        "step_order": 2,
        "position": {
          "id": 1,
          "name": "Ketua Jurusan"
        },
        "requires_attachment": true,
        "attachment_type": "Surat Disposisi dari Kaprodi"
      },
      "previous_approvals": [
        {
          "step_order": 1,
          "position": "Kaprodi",
          "approver_name": "Dr. Ahmad Syaiful Rohman",
          "approval_status": "Approved",
          "approved_at": "2026-04-28T08:15:00Z",
          "notes": "Disetujui. Sudah sesuai dengan SOP."
        }
      ],
      "documents_uploaded": [
        {
          "id": 10,
          "document_name": "Proposal Acara",
          "document_type": "proposal",
          "file_path": "storage/bookings/5/proposal_seminar.pdf",
          "file_size": "2.5 MB",
          "uploaded_at": "2026-04-28T10:35:00Z",
          "uploaded_by": "Rini Kusuma"
        },
        {
          "id": 11,
          "document_name": "Surat Resmi dari HMTI",
          "document_type": "surat_resmi",
          "file_path": "storage/bookings/5/surat_hmti.pdf",
          "file_size": "0.8 MB",
          "uploaded_at": "2026-04-28T10:38:00Z",
          "uploaded_by": "Rini Kusuma"
        }
      ],
      "priority_indicator": "high",
      "time_remaining": "17 days"
    },
    {
      "id": 2,
      "booking": {
        "id": 6,
        "event_name": "Workshop Frontend Development",
        "event_description": "Workshop intensif React dan Vue.js untuk mahasiswa",
        "booking_date": "2026-04-30",
        "start_time": "14:00",
        "end_time": "17:00",
        "status": "Pending",
        "current_step": 1,
        "revision_count": 0,
        "created_at": "2026-04-28T09:00:00Z"
      },
      "room": {
        "id": 5,
        "room_name": "Lab Komputer A",
        "room_code": "LAB-A",
        "capacity": 30,
        "building": {
          "id": 2,
          "building_name": "Gedung Akademik Blok C"
        }
      },
      "peminjam": {
        "id": 9,
        "name": "Budi Hartono",
        "nim": "2141720456",
        "email": "budi.hartono@student.polinema.ac.id",
        "unit": {
          "id": 4,
          "name": "BEM TI (Badan Eksekutif Mahasiswa TI)"
        }
      },
      "workflow": {
        "id": 1,
        "name": "Peminjaman JTI",
        "total_steps": 3
      },
      "current_approver_required": {
        "step_order": 1,
        "position": {
          "id": 2,
          "name": "Kaprodi"
        },
        "requires_attachment": false,
        "attachment_type": null
      },
      "previous_approvals": [],
      "documents_uploaded": [
        {
          "id": 12,
          "document_name": "Proposal Acara",
          "document_type": "proposal",
          "file_path": "storage/bookings/6/proposal_workshop.pdf",
          "file_size": "1.2 MB",
          "uploaded_at": "2026-04-28T09:05:00Z",
          "uploaded_by": "Budi Hartono"
        }
      ],
      "priority_indicator": "normal",
      "time_remaining": "2 days"
    },
    {
      "id": 3,
      "booking": {
        "id": 7,
        "event_name": "Rapat Koordinasi Unit Kegiatan",
        "event_description": "Koordinasi program kerja semester genap",
        "booking_date": "2026-04-29",
        "start_time": "15:00",
        "end_time": "17:00",
        "status": "Pending",
        "current_step": 2,
        "revision_count": 1,
        "created_at": "2026-04-27T14:30:00Z"
      },
      "room": {
        "id": 8,
        "room_name": "Meeting Room 2",
        "room_code": "MR-02",
        "capacity": 20,
        "building": {
          "id": 1,
          "building_name": "Gedung Rektorat"
        }
      },
      "peminjam": {
        "id": 10,
        "name": "Siti Nurhaliza",
        "nim": "2141720789",
        "email": "siti.nurhaliza@student.polinema.ac.id",
        "unit": {
          "id": 5,
          "name": "Organisasi Mahasiswa Sipil"
        }
      },
      "workflow": {
        "id": 1,
        "name": "Peminjaman JTI",
        "total_steps": 3
      },
      "current_approver_required": {
        "step_order": 2,
        "position": {
          "id": 1,
          "name": "Ketua Jurusan"
        },
        "requires_attachment": false,
        "attachment_type": null
      },
      "previous_approvals": [
        {
          "step_order": 1,
          "position": "Kaprodi",
          "approver_name": "Dr. Ahmad Syaiful Rohman",
          "approval_status": "Approved",
          "approved_at": "2026-04-27T15:45:00Z",
          "notes": "Disetujui dengan catatan"
        }
      ],
      "documents_uploaded": [
        {
          "id": 13,
          "document_name": "Proposal Acara",
          "document_type": "proposal",
          "file_path": "storage/bookings/7/proposal_rapat.pdf",
          "file_size": "0.9 MB",
          "uploaded_at": "2026-04-27T14:35:00Z",
          "uploaded_by": "Siti Nurhaliza"
        }
      ],
      "priority_indicator": "urgent",
      "time_remaining": "1 day",
      "revision_badge": "Revisi 1 dari Editor"
    }
  ]
}
```

**Error Response (401):**
```json
{
  "success": false,
  "message": "Unauthorized. Only Approver role can access this endpoint."
}
```

---

### POST /api/approvals/{booking_id}/approve
Approve a booking at current step.

**Request:**
```json
{
  "notes": "Disetujui. Dokumentasi lengkap dan terverifikasi.",
  "attachment": "[multipart file if requires_attachment = true]"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Booking berhasil disetujui.",
  "booking": {
    "id": 5,
    "status": "Pending",
    "current_step": 3,
    "next_approver": {
      "step_order": 3,
      "position": "Wakil Direktur",
      "requires_attachment": true
    }
  },
  "log": {
    "id": 45,
    "booking_id": 5,
    "action": "APPROVED",
    "actor": "Dr. Bambang Suryanto",
    "notes": "Disetujui. Dokumentasi lengkap dan terverifikasi.",
    "created_at": "2026-04-28T11:00:00Z"
  }
}
```

**Error (422 Validation):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "attachment": ["File attachment required for this step"]
  }
}
```

---

### POST /api/approvals/{booking_id}/reject
Reject a booking (requires notes).

**Request:**
```json
{
  "notes": "Proposal belum memenuhi standar. Silakan perbaiki poin no. 3 dan kirim ulang."
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Booking ditolak. Menunggu revisi dari peminjam.",
  "booking": {
    "id": 6,
    "status": "Revising",
    "current_step": 1,
    "revision_count": 1
  },
  "rejection_log": {
    "id": 46,
    "booking_id": 6,
    "action": "REJECTED",
    "actor": "Dr. Bambang Suryanto",
    "actor_position": "Ketua Jurusan",
    "notes": "Proposal belum memenuhi standar. Silakan perbaiki poin no. 3 dan kirim ulang.",
    "created_at": "2026-04-28T11:15:00Z"
  }
}
```

---

### GET /api/approvals/{booking_id}
Get detailed booking for approval review.

**Response (200 OK):**
```json
{
  "success": true,
  "booking": {
    "id": 5,
    "event_name": "Seminar Cybersecurity 2026",
    "event_description": "Seminar internasional menghadirkan pembicara dari Google & Microsoft",
    "booking_date": "2026-05-15",
    "start_time": "09:00",
    "end_time": "12:00",
    "status": "Pending",
    "current_step": 2,
    "revision_count": 0,
    "room": {
      "id": 3,
      "room_name": "Auditorium Utama",
      "room_code": "AUD-001",
      "capacity": 500,
      "floor": 1,
      "facilities": ["Projector 4K", "Sound System", "AC", "WiFi"],
      "building": {
        "id": 1,
        "building_name": "Gedung Rektorat",
        "address": "Jl. Raya Tlogomas No. 246, Malang"
      }
    },
    "peminjam": {
      "id": 8,
      "name": "Rini Kusuma",
      "nim": "2141720123",
      "email": "rini.kusuma@student.polinema.ac.id",
      "phone": "087812345678",
      "unit": {
        "id": 3,
        "name": "HMTI (Himpunan Mahasiswa Teknik Informatika)"
      }
    },
    "workflow": {
      "id": 2,
      "name": "Peminjaman Auditorium",
      "total_steps": 2,
      "steps": [
        {
          "step_order": 1,
          "position": "Kaprodi",
          "requires_attachment": false
        },
        {
          "step_order": 2,
          "position": "Ketua Jurusan",
          "requires_attachment": true
        }
      ]
    },
    "approval_timeline": {
      "step_1": {
        "position": "Kaprodi",
        "approver_name": "Dr. Ahmad Syaiful Rohman",
        "approval_status": "Approved",
        "approved_at": "2026-04-28T08:15:00Z",
        "notes": "Disetujui. Sudah sesuai dengan SOP.",
        "signature_image_url": "storage/approvals/sign_1.png"
      },
      "step_2": {
        "position": "Ketua Jurusan",
        "approver_name": "Dr. Bambang Suryanto",
        "approval_status": "Pending",
        "requires_attachment": true,
        "attachment_description": "Surat Disposisi dari Kaprodi"
      }
    },
    "documents": [
      {
        "id": 10,
        "booking_id": 5,
        "document_name": "Proposal Acara",
        "file_type": "pdf",
        "file_size": "2.5 MB",
        "file_url": "storage/bookings/5/proposal_seminar.pdf",
        "uploaded_by": "Rini Kusuma",
        "uploaded_at": "2026-04-28T10:35:00Z",
        "is_mandatory": true
      },
      {
        "id": 11,
        "booking_id": 5,
        "document_name": "Surat Resmi dari HMTI",
        "file_type": "pdf",
        "file_size": "0.8 MB",
        "file_url": "storage/bookings/5/surat_hmti.pdf",
        "uploaded_by": "Rini Kusuma",
        "uploaded_at": "2026-04-28T10:38:00Z",
        "is_mandatory": true
      }
    ],
    "created_at": "2026-04-28T10:30:00Z",
    "updated_at": "2026-04-28T10:45:00Z"
  }
}
```

---

## Room Management

### GET /api/rooms/available
Search for available rooms based on date and time.

**Query Parameters:**
- `date` (required): Y-m-d format (e.g., `2026-05-20`)
- `start` (required): H:i format (e.g., `09:00`)
- `end` (required): H:i format (e.g., `12:00`)
- `building_id` (optional): Filter by building
- `capacity_min` (optional): Minimum capacity

**Request:**
```
GET /api/rooms/available?date=2026-05-20&start=09:00&end=12:00
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
  "success": true,
  "search_params": {
    "date": "2026-05-20",
    "start": "09:00",
    "end": "12:00",
    "duration_hours": 3
  },
  "count": 4,
  "available_rooms": [
    {
      "id": 3,
      "room_name": "Auditorium Utama",
      "room_code": "AUD-001",
      "capacity": 500,
      "floor": 1,
      "building": {
        "id": 1,
        "building_name": "Gedung Rektorat",
        "building_code": "GR"
      },
      "facilities": ["Projector 4K", "Sound System", "AC", "WiFi", "WiFi6"],
      "booking_workflow": {
        "id": 2,
        "workflow_name": "Peminjaman Auditorium",
        "total_steps": 2
      },
      "owner_unit": {
        "id": 1,
        "unit_name": "Pusat"
      }
    },
    {
      "id": 5,
      "room_name": "Lab Komputer A",
      "room_code": "LAB-A",
      "capacity": 30,
      "floor": 2,
      "building": {
        "id": 2,
        "building_name": "Gedung Akademik Blok C"
      },
      "facilities": ["AC", "WiFi", "Komputer (30 unit)", "Proyektor"],
      "booking_workflow": {
        "id": 1,
        "workflow_name": "Peminjaman JTI",
        "total_steps": 3
      },
      "owner_unit": {
        "id": 2,
        "unit_name": "Jurusan Teknik Informatika"
      }
    },
    {
      "id": 8,
      "room_name": "Meeting Room 2",
      "room_code": "MR-02",
      "capacity": 20,
      "floor": 1,
      "building": {
        "id": 1,
        "building_name": "Gedung Rektorat"
      },
      "facilities": ["AC", "WiFi", "Meja Bundar", "Whiteboard"],
      "booking_workflow": {
        "id": 1,
        "workflow_name": "Peminjaman JTI",
        "total_steps": 3
      },
      "owner_unit": {
        "id": 1,
        "unit_name": "Pusat"
      }
    },
    {
      "id": 10,
      "room_name": "Studio Fotografi",
      "room_code": "STUDIO-01",
      "capacity": 10,
      "floor": 3,
      "building": {
        "id": 3,
        "building_name": "Gedung Teknik"
      },
      "facilities": ["AC", "WiFi", "Lighting", "Green Screen", "Photography Equipment"],
      "booking_workflow": {
        "id": 1,
        "workflow_name": "Peminjaman JTI",
        "total_steps": 3
      },
      "owner_unit": {
        "id": 2,
        "unit_name": "Jurusan Teknik Informatika"
      }
    }
  ]
}
```

---

## Booking Management

### POST /api/bookings
Create a new booking (Soft-Lock triggered).

**Request:**
```json
{
  "room_id": 3,
  "event_name": "Seminar Cybersecurity 2026",
  "event_description": "Seminar internasional dengan pembicara dari Google & Microsoft",
  "booking_date": "2026-05-15",
  "start_time": "09:00",
  "end_time": "12:00"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Booking berhasil dibuat. Status: Soft-Lock untuk 30 menit.",
  "booking": {
    "id": 5,
    "user_id": 8,
    "room_id": 3,
    "event_name": "Seminar Cybersecurity 2026",
    "booking_date": "2026-05-15",
    "start_time": "09:00",
    "end_time": "12:00",
    "status": "Draft",
    "current_step": 0,
    "workflow_id": 2,
    "soft_lock_expires_at": "2026-04-28T11:30:00Z"
  }
}
```

---

### POST /api/bookings/{id}/submit
Submit booking with documents.

**Request (multipart/form-data):**
```
booking_id: 5
documents[0]: {file: proposal.pdf}
documents[1]: {file: surat_resmi.pdf}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Booking berhasil diajukan ke alur persetujuan.",
  "booking": {
    "id": 5,
    "status": "Pending",
    "current_step": 1,
    "workflow": {
      "id": 2,
      "name": "Peminjaman Auditorium",
      "total_steps": 2,
      "next_approver": {
        "step_order": 1,
        "position": "Kaprodi",
        "position_id": 2
      }
    }
  },
  "documents_uploaded": [
    {
      "id": 10,
      "document_name": "Proposal Acara",
      "file_url": "storage/bookings/5/proposal_seminar.pdf"
    },
    {
      "id": 11,
      "document_name": "Surat Resmi dari HMTI",
      "file_url": "storage/bookings/5/surat_hmti.pdf"
    }
  ]
}
```

---

### GET /api/bookings/{id}
Get booking detail with full approval chain.

**Response (200 OK):**
```json
{
  "success": true,
  "booking": {
    "id": 5,
    "event_name": "Seminar Cybersecurity 2026",
    "booking_date": "2026-05-15",
    "start_time": "09:00",
    "end_time": "12:00",
    "status": "Pending",
    "current_step": 1,
    "revision_count": 0,
    "room": {
      "id": 3,
      "room_name": "Auditorium Utama",
      "building": "Gedung Rektorat"
    },
    "workflow": {
      "id": 2,
      "name": "Peminjaman Auditorium",
      "total_steps": 2,
      "current_step_info": {
        "step_order": 1,
        "position": "Kaprodi",
        "requires_attachment": false
      }
    },
    "documents": [
      {
        "id": 10,
        "document_name": "Proposal Acara",
        "file_url": "storage/bookings/5/proposal_seminar.pdf",
        "uploaded_at": "2026-04-28T10:35:00Z"
      }
    ]
  }
}
```

---

## Workflow Management

### GET /api/workflows/{id}/requirements
Get document requirements for a workflow.

**Response (200 OK):**
```json
{
  "success": true,
  "workflow": {
    "id": 2,
    "name": "Peminjaman Auditorium",
    "total_steps": 2
  },
  "requirements": [
    {
      "id": 1,
      "workflow_id": 2,
      "document_name": "Proposal Acara",
      "description": "Proposal acara lengkap dengan detail kegiatan, peserta, dan timeline",
      "is_mandatory": true,
      "max_file_size": "5 MB",
      "allowed_formats": ["pdf", "doc", "docx"]
    },
    {
      "id": 2,
      "workflow_id": 2,
      "document_name": "Surat Resmi dari Unit",
      "description": "Surat resmi dari unit/organisasi peminjam yang ditandatangani pimpinan",
      "is_mandatory": true,
      "max_file_size": "5 MB",
      "allowed_formats": ["pdf", "doc", "docx", "jpg", "png"]
    },
    {
      "id": 3,
      "workflow_id": 2,
      "document_name": "Persetujuan Dosen Pembimbing",
      "description": "Persetujuan dari dosen pembimbing (jika ada)",
      "is_mandatory": false,
      "max_file_size": "5 MB",
      "allowed_formats": ["pdf", "jpg", "png"]
    }
  ]
}
```

---

## Approval Management

### GET /api/approvals/stats
Get approval statistics dashboard.

**Response (200 OK):**
```json
{
  "success": true,
  "stats": {
    "pending_count": 3,
    "approved_count": 12,
    "rejected_count": 2,
    "average_approval_time_hours": 24.5,
    "monthly_trend": [
      {
        "month": "March",
        "approved": 8,
        "rejected": 1
      },
      {
        "month": "April",
        "approved": 12,
        "rejected": 2
      }
    ]
  }
}
```

---

## Timeline & History

### GET /api/bookings/{id}/approvals
Get approval timeline for a booking.

**Response (200 OK):**
```json
{
  "success": true,
  "booking_id": 5,
  "timeline": [
    {
      "id": 40,
      "step_order": 1,
      "position": "Kaprodi",
      "approver_name": "Dr. Ahmad Syaiful Rohman",
      "approval_status": "Approved",
      "notes": "Disetujui. Sudah sesuai dengan SOP.",
      "approved_at": "2026-04-28T08:15:00Z",
      "signature_image_url": "storage/approvals/sign_kaprodi.png",
      "attempt": 1
    },
    {
      "id": 41,
      "step_order": 2,
      "position": "Ketua Jurusan",
      "approver_name": "Dr. Bambang Suryanto",
      "approval_status": "Pending",
      "notes": null,
      "approved_at": null,
      "signature_image_url": null,
      "requires_attachment": true,
      "attachment_description": "Surat Disposisi dari Kaprodi",
      "attempt": 1
    }
  ]
}
```

---

## Error Codes

| Code | Message | Description |
|------|---------|-------------|
| 200 | Success | Request successful |
| 201 | Created | Resource created successfully |
| 400 | Bad Request | Invalid request parameters |
| 401 | Unauthorized | Missing or invalid authentication |
| 403 | Forbidden | User doesn't have permission |
| 404 | Not Found | Resource not found |
| 409 | Conflict | Booking time slot conflict |
| 422 | Validation Error | Validation failed |
| 500 | Server Error | Internal server error |

---

## Authentication Headers

All API requests (except login) require:
```
Authorization: Bearer {access_token}
Content-Type: application/json
```

---

## Rate Limiting

- **Limit:** 100 requests per minute per user
- **Header:** `X-RateLimit-Remaining`

---

## Base URL

```
https://api.spacein.test
```

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2026-04-28 | Initial API documentation with mock responses |

