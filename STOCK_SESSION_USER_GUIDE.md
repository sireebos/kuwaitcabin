# Stock Session System - Quick Start Guide

## For Bar Staff

### 🎯 Start Your Day: Open a Stock Session

1. Navigate to **Properties → [Your Property] → Stock Sessions**
2. Click **"New Session"** button
3. Fill out the form:
   - **Session Date**: Today's date (auto-filled)
   - **Select Items**: Click "Add Item" to select inventory items you want to track
   - **Opening Quantity**: Enter the physical count for each item
   - **Notes** (Optional): Add any notes about the session

4. **Option**: If continuing from yesterday, check "Use Previous Session Balance" to auto-fill quantities
5. Click **"Open Session"**

---

### 📊 During Your Shift: Track Usage

**The system automatically tracks usage when:**
- Orders are placed and linked to the session
- You manually record adjustments

**To record a manual adjustment:**
1. Go to your active session
2. Click the "Adjustment" button (coming soon - integration with orders)
3. Select the item, quantity, and reason
4. The system updates the expected closing amount

---

### 📝 End Your Shift: Close the Session

1. Go to your active session
2. Click **"Close Session"** button
3. Enter the **actual physical count** for each item
4. Review the **Expected Closing** column to verify
5. Click **"Confirm & Close Session"**

The system will:
- Calculate variance (actual vs. expected)
- Lock the session from further edits
- Generate a closing balance

---

### 📈 View Your Report

1. Go to a closed session
2. Click **"Report"** button
3. Review:
   - Summary cards (Opening/Closing/Usage values)
   - Detailed breakdown of each item
   - Variance analysis
   - Session information

4. Click **"Print"** to generate a PDF

---

## For Administrators

### 👁️ Monitoring Stock Sessions

1. **View All Sessions**: Go to **Stock Sessions** list
2. **Check Status**: 
   - 🟢 "Open" = Currently in use
   - ⚫ "Closed" = Finalized with variance calculated
3. **View Details**: Click "View Details" on any session
4. **View Report**: Click "Report" on closed sessions

### 📊 Analyze Variance

Variance shows the difference between expected and actual closing stock:
- **Positive variance** = Gain (more stock than expected)
- **Negative variance** = Loss (less stock than expected)

Look for patterns in variance to identify:
- Inventory shrinkage
- Spillage/waste issues
- Counting accuracy
- Theft prevention

---

## Field Explanations

### When Opening a Session

| Field | Meaning |
|-------|---------|
| Opening Quantity | Physical count at start of shift |
| Use Previous Balance | Auto-fill from yesterday's closing count |

### During Session

| Field | Meaning |
|-------|---------|
| Quantity Used | How much was used in orders |
| Manual Adjustment | Corrections (+/-) with reason |
| Expected Closing | Opening + Received - Used + Adjustment |

### When Closing

| Field | Meaning |
|-------|---------|
| Actual Closing | Physical count at end of shift |
| Expected Closing | System's calculated count |
| Variance | Difference (Actual - Expected) |

---

## Common Scenarios

### 📌 Scenario 1: Spillage/Waste
1. Item count is lower than expected
2. During close, enter actual physical count (lower number)
3. System shows negative variance
4. Add a note like "Spilled 1 bottle of vodka"

### 📌 Scenario 2: Restocking Mid-Session
1. You receive new inventory during shift
2. Go to active session → Record Adjustment
3. Add quantity, select reason "Received Stock"
4. This adjusts the expected closing upward

### 📌 Scenario 3: Counting Error
1. You miscounted at opening
2. Go to active session → Record Adjustment
3. Enter the correction amount
4. Add reason "Opening Count Correction"

### 📌 Scenario 4: Next Day - Continuing Session
1. Open New Session
2. Check "Use Previous Session Balance"
3. Yesterday's closing becomes today's opening
4. Reduces manual re-entry and ensures continuity

---

## Tips & Best Practices

✅ **DO:**
- Open session at start of shift
- Close session at end of shift (don't leave open overnight)
- Enter opening counts immediately (from physical count, not memory)
- Document adjustments with clear reasons
- Close within 1-2 hours of shift end for accuracy
- Use continuous sessions to track trends

❌ **DON'T:**
- Estimate opening quantities - always count physically
- Forget to close session at end of shift
- Leave sessions open for multiple days
- Make adjustments without documenting reason
- Close session days later (counts become inaccurate)

---

## Error Messages & Solutions

| Error | Cause | Solution |
|-------|-------|----------|
| "Session already exists for today" | You have an open session | Close the existing session first |
| "Cannot edit closed session" | Session is finalized | Variance is locked, create new session |
| "Session date is in the future" | Invalid date | Select today's date or past date |
| "Please select at least one item" | No items added | Click "Add Item" to select items |

---

## Keyboard Shortcuts (Coming Soon)

| Shortcut | Action |
|----------|--------|
| `Ctrl+N` | New Session |
| `Ctrl+P` | Print Report |
| `Ctrl+S` | Save/Submit |

---

## FAQ

**Q: Can I edit a closed session?**
A: No, closed sessions are locked. Create a new session to make corrections.

**Q: What if I made a counting error at opening?**
A: Record a manual adjustment immediately to correct the opening count.

**Q: How do I know if my session closed correctly?**
A: Check the variance percentage. Usually < 5% is acceptable. Anything over 10% warrants investigation.

**Q: Can I view previous staff members' sessions?**
A: Only administrators can view other staff's sessions. You can only see your own.

**Q: What causes variance?**
A: Natural causes (evaporation, spillage), measurement error, unrecorded usage, or inventory shrinkage.

---

## Support

For issues or questions:
1. Check the "View Details" page of your session
2. Review the printed report
3. Contact your property administrator
4. Check the help documentation

---

**Version**: 1.0  
**Last Updated**: December 2024  
**Status**: ✅ Live & Ready
