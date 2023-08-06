/**
 *
 * RowsServerSide
 *
 * Interface.Plugins.Datatables.RowsServerSide.html page content scripts. Initialized from scripts.js file.
 *
 *
 */

class RowsServerSide {
  constructor() {

    if (!jQuery().DataTable) {
      console.log('DataTable is null!');
      return;
    }

    if (!document.getElementById('datatableServerSideUrl'))
      return;
    // Selected single row which will be edited
    this._rowToEdit;
    // Datatable instance
    this._datatable;

    // Edit or add state of the modal
    this._currentState;

    // Controls and select helper
    this._datatableExtend;

    // Add or edit modal
    this._addModal;
    this._editModal;

    // Assign modal
    this._assignModal;

    // Close Request modal
    this._closeRequestModal;

    // Close Request modal
    this._secretReturnModal;

    // Need Info modal
    this._needInfoModal;


    this._addAssignAdminModal;

    // Datatable single item height
    this._staticHeight = 62;

    // Path to the api for getting and setting items
    this._apiPath = 'https://node-api.coloredstrategies.com';

    this._createInstance();
    this._addListeners();
    this._extend();
    this._initBootstrapModal();
  }

  // Creating datatable instance
  _createInstance() {
    const _this = this;

    this._datatable = jQuery('#datatableServerSideUrl').DataTable({
      scrollX: true,
      buttons: ['copy', 'excel', 'csv', 'print'],
      info: false,
      processing: true,
      serverSide: true,
      ajax: {
        'url': window.datatableServerSideUrl,
        'type': 'GET',
        'data': function (d) {
          if (typeof window.filter != 'undefined') {
            Object.keys(window.filter).forEach((key, i) => {
              d[key] = Object.values(window.filter)[i];
            });
          }
        },
        'beforeSend': function (request) {
          Object.keys(window.ajaxHeaders).map((key, i) => {
            request.setRequestHeader(key, Object.values(window.ajaxHeaders)[i]);
          });
        },
        // "error": function () {
        //   window.location.href = "/notFound";
        // }
      },
      sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>', // Hiding all other dom elements except table and pagination
      pageLength: 10,
      scrollY: "40vh",
      scrollCollapse: "true",
      columns: window.datatableServerSideColumns,
      order: [[0, "desc"]],
      language: {
        paginate: {
          previous: '<i class="cs-chevron-left"></i>',
          next: '<i class="cs-chevron-right"></i>',
        },
      },
      initComplete: function (settings, json) {
        _this._setInlineHeight();
      },
      drawCallback: function (settings) {
        _this._setInlineHeight();
      },
      columnDefs: window.datatableServerSideColumnDefs || []
    });
    $(".checkInput").on("click", function () {
      const SelectedRow = this._datatableExtend.getSelectedRows();
      // this._onEditRowClick(this._datatable.row(selected[0][0]));
      const thisRow = this._datatable.row(selected[0][0]).data();
    });
  }

  _addListeners() {
    let closeButtons = document.getElementsByClassName('closeAndReload');
    if (closeButtons != null && closeButtons.length) {
      Object.values(closeButtons).forEach(btn => {
        btn.addEventListener('click', this._closeModalReloadData.bind(this));
      })
    }

    // TODO:: remove next
    // Listener for confirm button on the edit/add modal
    if (document.getElementById('addEditConfirmButton'))
      document.getElementById('addEditConfirmButton').addEventListener('click', this._addEditFromModalClick.bind(this));

    // Listener for confirm button on the edit/add modal
    if (document.getElementById('assignButton'))
      document.getElementById('assignButton').addEventListener('click', this._assignFromModalClick.bind(this));

    // Listener for confirm button on the need info modal
    if (document.getElementById('needInfoButton'))
      document.getElementById('needInfoButton').addEventListener('click', this._needInfoFromModalClick.bind(this));

    // Listener for add buttons
    if (document.querySelectorAll('.add-datatable'))
      document.querySelectorAll('.add-datatable').forEach((el) => el.addEventListener('click', this._onAddRowClick.bind(this)));

    // Listener for assign buttons
    if (document.querySelectorAll('.assign-datatable'))
      document.querySelectorAll('.assign-datatable').forEach((el) => el.addEventListener('click', this._onAssignRowClick.bind(this)));

    // Listener for need info buttons
    if (document.querySelectorAll('.need-info-datatable'))
      document.querySelectorAll('.need-info-datatable').forEach((el) => el.addEventListener('click', this._onNeedInfoRowClick.bind(this)));

    // Listener for delete buttons
    if (document.querySelectorAll('.delete-datatable'))
      document.querySelectorAll('.delete-datatable').forEach((el) => el.addEventListener('click', this._onDeleteClick.bind(this)));

    // Listener for delete buttons
    if (document.querySelectorAll('.change-password-datatable'))
      document.querySelectorAll('.change-password-datatable').forEach((el) => el.addEventListener('click', this._onChangePasswordClick.bind(this)));

    // Listener for delete assigned buttons
    if (document.querySelectorAll('.delete-assigned-datatable'))
      document.querySelectorAll('.delete-assigned-datatable').forEach((el) => el.addEventListener('click', this._onAssignedDeleteClick.bind(this)));

    // Listener for restore buttons
    if (document.querySelectorAll('.restore-datatable'))
      document.querySelectorAll('.restore-datatable').forEach((el) => el.addEventListener('click', this._onRestoreClick.bind(this)));

    // Listener for edit button
    if (document.querySelectorAll('.edit-datatable'))
      document.querySelectorAll('.edit-datatable').forEach((el) => el.addEventListener('click', this._onEditButtonClick.bind(this)));

    // Listener for edit button
    if (document.querySelectorAll('.edit-template-datatable'))
      document.querySelectorAll('.edit-template-datatable').forEach((el) => el.addEventListener('click', this._onEditTemplateButtonClick.bind(this)));

    // Listener for admin template button
    if (document.querySelectorAll('.admin-datatable'))
      document.querySelectorAll('.admin-datatable').forEach((el) => el.addEventListener('click', this._onAdminButtonClick.bind(this)));

    // Listener for review button
    if (document.querySelectorAll('.review-datatable'))
      document.querySelectorAll('.review-datatable').forEach((el) => el.addEventListener('click', this._onReviewButtonClick.bind(this)));

    if (document.querySelectorAll('.message-datatable'))
      document.querySelectorAll('.message-datatable').forEach((el) => el.addEventListener('click', this._onViewMessageButtonClick.bind(this)));

    // Listener for assign button
    if (document.querySelectorAll('.assign-datatable'))
      document.querySelectorAll('.assign-datatable').forEach((el) => el.addEventListener('click', this._onAssignButtonClick.bind(this)));
    // Listener for need info button
    if (document.querySelectorAll('.need-info-datatable'))
      document.querySelectorAll('.need-info-datatable').forEach((el) => el.addEventListener('click', this._onNeedInfoButtonClick.bind(this)));

    // Listener for close request button
    if (document.querySelectorAll('.close-request-datatable'))
      document.querySelectorAll('.close-request-datatable').forEach((el) => el.addEventListener('click', this._onCloseRequestButtonClick.bind(this)));

    // Listener for secret return request button
    if (document.querySelectorAll('.approve-secret-datatable'))
      document.querySelectorAll('.approve-secret-datatable').forEach((el) => el.addEventListener('click', this._onApproveReturnRequestButtonClick.bind(this)));

    // Calling a function to update tags on click
    // document.querySelectorAll('.tag-done').forEach((el) => el.addEventListener('click', () => this._updateTag('Done')));
    // document.querySelectorAll('.tag-new').forEach((el) => el.addEventListener('click', () => this._updateTag('New')));
    // document.querySelectorAll('.tag-sale').forEach((el) => el.addEventListener('click', () => this._updateTag('Sale')));

    // Calling clear form when modal is closed
    if (document.getElementById('addModal'))
      document.getElementById('addModal').addEventListener('hidden.bs.modal', this._clearModalForm);
    if (document.getElementById('assignModal'))
      document.getElementById('assignModal').addEventListener('hidden.bs.modal', this._clearAssignModalForm);
    if (document.getElementById('needInfoModal'))
      document.getElementById('needInfoModal').addEventListener('hidden.bs.modal', this._clearNeedInfoModalForm);
  }

  // Extending with DatatableExtend to get search, select and export working
  _extend() {
    this._datatableExtend = new DatatableExtend({
      datatable: this._datatable,
      editRowCallback: this._onEditRowClick.bind(this),
      singleSelectCallback: this._onSingleSelect.bind(this),
      multipleSelectCallback: this._onMultipleSelect.bind(this),
      anySelectCallback: this._onAnySelect.bind(this),
      noneSelectCallback: this._onNoneSelect.bind(this),
    });
  }

  // Keeping a reference to add/edit modal
  _initBootstrapModal() {
    this._addModal = new bootstrap.Modal(document.getElementById('addModal'), { backdrop: false });

    if (document.getElementById('assignModal'))
      this._assignModal = new bootstrap.Modal(document.getElementById('assignModal'), { backdrop: false });

    if (document.getElementById('editModal'))
      this._editModal = new bootstrap.Modal(document.getElementById('editModal'), { backdrop: false });

    if (document.getElementById('closeRequestModal'))
      this._closeRequestModal = new bootstrap.Modal(document.getElementById('closeRequestModal'), { backdrop: false });

    if (document.getElementById('secretReturnModal'))
      this._secretReturnModal = new bootstrap.Modal(document.getElementById('secretReturnModal'), { backdrop: false });

    if (document.getElementById('needInfoModal'))
      this._needInfoModal = new bootstrap.Modal(document.getElementById('needInfoModal'), { backdrop: false });

    if (document.getElementById('adminsModal'))
      this._addAssignAdminModal = new bootstrap.Modal(document.getElementById('adminsModal'), { backdrop: false });
  }

  // Setting static height to datatable to prevent pagination movement when list is not full
  _setInlineHeight() {
    if (!this._datatable) {
      return;
    }
    const pageLength = this._datatable.page.len();
    document.querySelector('.dataTables_scrollBody').style.height = this._staticHeight * pageLength + 'px';
  }

  // Showing spinner for server side operations
  _addSpinner() {
    document.body.classList.add('spinner');
  }

  // Removing spinner after completing server side operations
  _removeSpinner() {
    document.body.classList.remove('spinner');
  }

  _closeModalReloadData(event) {
    this._datatable.draw();
    this._datatableExtend.unCheckAllRows();
    this._datatableExtend.controlCheckAll();
    if (this._addModal)
      this._addModal.hide();
    if (this._assignModal)
      this._assignModal.hide();
    if (this._closeRequestModal)
      this._closeRequestModal.hide();
    if (this._secretReturnModal)
      this._secretReturnModal.hide();
    if (this._needInfoModal)
      this._needInfoModal.hide();
    if (this._editModal)
      this._editModal.hide();
    if (this._addAssignAdminModal)
      this._addAssignAdminModal.hide();
  }

  // TODO:: remove next and use general one
  // Add or edit button inside the modal click
  _addEditFromModalClick(event) {
    // if (this._currentState === 'add') {
    //   this._addNewRowFromModal();
    // } else {
    //   this._editRowFromModal();
    // }
    this._datatable.draw();
    this._datatableExtend.unCheckAllRows();
    this._datatableExtend.controlCheckAll();
    this._addModal.hide();
  }

  // Assign button inside the modal click
  _assignFromModalClick(event) {
    this._datatable.draw();
    this._datatableExtend.unCheckAllRows();
    this._datatableExtend.controlCheckAll();
    this._assignModal.hide();
  }

  // need info button inside the modal click
  _needInfoFromModalClick(event) {
    this._datatable.draw();
    this._datatableExtend.unCheckAllRows();
    this._datatableExtend.controlCheckAll();
    this._needInfoModal.hide();
  }

  // Top side edit icon click
  _onEditButtonClick(event) {
    if (event.currentTarget.classList.contains('disabled')) {
      return;
    }
    const selected = this._datatableExtend.getSelectedRows();
    // this._onEditRowClick(this._datatable.row(selected[0][0]));
    let row = this._datatable.row(selected[0][0]).data();
    console.log(row)
    window.datatableServerSideEdit(event, row)
  }

  // Top side edit icon click
  _onEditTemplateButtonClick(event) {
    if (event.currentTarget.classList.contains('disabled')) {
      return;
    }
    const selected = this._datatableExtend.getSelectedRows();
    // this._onEditRowClick(this._datatable.row(selected[0][0]));
    let row = this._datatable.row(selected[0][0]).data();
    console.log(row)
    window.datatableServerSideEditTemplate(event, row)
  }

  // Top side edit icon click
  _onAdminButtonClick(event) {
    if (event.currentTarget.classList.contains('disabled')) {
      return;
    }
    const selected = this._datatableExtend.getSelectedRows();
    // this._onEditRowClick(this._datatable.row(selected[0][0]));
    let row = this._datatable.row(selected[0][0]).data();
    window.datatableServerSideAssignAdmins(event, row)
  }

  // Top side edit icon click
  _onNeedInfoButtonClick(event) {
    if (event.currentTarget.classList.contains('disabled')) {
      return;
    }
    const selected = this._datatableExtend.getSelectedRows();
    let row = this._datatable.row(selected[0][0]).data();
    window.datatableServerSideNeedInfo(event, row)
  }

  // Top side review icon click
  _onReviewButtonClick(event) {
    if (event.currentTarget.classList.contains('disabled')) {
      return;
    }
    const selected = this._datatableExtend.getSelectedRows();
    // this._onEditRowClick(this._datatable.row(selected[0][0]));
    let row = this._datatable.row(selected[0][0]).data();
    window.datatableServerSideReview(event, row)
  }

  _onViewMessageButtonClick(event) {
    if (event.currentTarget.classList.contains('disabled')) {
      return;
    }
    const selected = this._datatableExtend.getSelectedRows();
    // this._onEditRowClick(this._datatable.row(selected[0][0]));
    let row = this._datatable.row(selected[0][0]).data();
    window.datatableServerSideViewMessage(event, row)
  }

  // Top side assign icon click
  _onAssignButtonClick() {

    const selected = this._datatableExtend.getSelectedRows();
    const data = selected.data();
    let row = this._datatable.row(selected[0][0]).data();
    let ids = [];
    let requesterIds = [];
    let permissions = [];
    for (let i = 0; i < data.length; i++) {
      ids.push(data[i].id);
      requesterIds.push(data[i].user_id);
      permissions.push(data[i].name);
    }
    window.datatableServerSideAssign(row, ids, requesterIds, permissions);

    // if (event.currentTarget.classList.contains('disabled')) {
    //   return;
    // }
    // const selected = this._datatableExtend.getSelectedRows();
    // // this._onEditRowClick(this._datatable.row(selected[0][0]));
    // let row = this._datatable.row(selected[0][0]).data();
    // window.datatableServerSideAssign(event, row)
  }

  // Top side close request icon click
  _onCloseRequestButtonClick(event) {
    if (event.currentTarget.classList.contains('disabled')) {
      return;
    }
    const selected = this._datatableExtend.getSelectedRows();
    // this._onEditRowClick(this._datatable.row(selected[0][0]));
    let row = this._datatable.row(selected[0][0]).data();
    window.datatableServerSideCloseRequest(event, row)

  }

  // Top side approve secret request icon click
  _onApproveReturnRequestButtonClick(event) {
    if (event.currentTarget.classList.contains('disabled')) {
      return;
    }
    const selected = this._datatableExtend.getSelectedRows();
    // this._onEditRowClick(this._datatable.row(selected[0][0]));
    let row = this._datatable.row(selected[0][0]).data();
    window.datatableServerSideApproveSecretRequest(event, row)

  }

  // Direct click from row title
  _onEditRowClick(rowToEdit) {
    this._rowToEdit = rowToEdit; // Passed from DatatableExtend via callback from settings
    this._showModal('edit', 'Edit', 'Done');
    this._setForm();
  }

  _onAssignAdminsRowClick(row) {
    // this._rowToEdit = rowToEdit; // Passed from DatatableExtend via callback from settings
    console.log('row is :', row)
  }

  // Edit button inside th modal click
  _editRowFromModal() {
    const data = this._rowToEdit.data();
    const formData = Object.assign(data, this._getFormData());
    this._addSpinner();
    fetch(this._apiPath + '/products/update', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    })
      .then((response) => {
        response.json();
        this._removeSpinner();
      })
      .then((data) => {
        this._datatable.draw();
      })
      .catch((error) => {
        // console.error('Error:', error);
      });
    this._datatableExtend.unCheckAllRows();
    this._datatableExtend.controlCheckAll();
  }

  // Add button inside th modal click
  _addNewRowFromModal() {
    const data = this._getFormData();
    this._addSpinner();
    fetch(this._apiPath + '/products/add', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    })
      .then((response) => {
        response.json();
        this._removeSpinner();
      })
      .then((data) => {
        this._datatable.draw();
      })
      .catch((error) => {
        // console.error('Error:', error);
      });
    this._datatableExtend.unCheckAllRows();
    this._datatableExtend.controlCheckAll();
  }

  // Delete icon click
  _onDeleteClick() {
    const selected = this._datatableExtend.getSelectedRows();
    const data = selected.data();
    let ids = [];
    for (let i = 0; i < data.length; i++) {
      ids.push(data[i].id);
    }
    window.datatableServerSideDelete(ids);
  }

  // Delete Assigned icon click
  _onAssignedDeleteClick() {
    const selected = this._datatableExtend.getSelectedRows();
    const data = selected.data();
    let ids = [];
    for (let i = 0; i < data.length; i++) {
      ids.push(data[i].assigned.id);
    }
    window.datatableServerSideDelete(ids);
  }

  _onRestoreClick() {
    const selected = this._datatableExtend.getSelectedRows();
    const data = selected.data();
    let ids = [];
    for (let i = 0; i < data.length; i++) {
      ids.push(data[i].id);
    }
    window.datatableServerSideRestore(ids);
  }

  _onChangePasswordClick(event) {
    if (event.currentTarget.classList.contains('disabled')) {
      return;
    }
    const selected = this._datatableExtend.getSelectedRows();
    let row = this._datatable.row(selected[0][0]).data();
    window.datatableServerSideChangePassword(event, row)
  }

  // + Add New or just + button from top side click
  _onAddRowClick() {
    this._showModal('add', 'Add New', 'Add');
  }

  // + assign New or just + button from top side click
  _onAssignRowClick() {
    this._showAssignModal('assign', 'Approve', 'Assign');
  }

  // + assign New or just + button from top side click
  _onNeedInfoRowClick() {
    this._showNeedInfoModal('needinfo', 'Need Info', 'needInfo');
  }

  // Showing modal for an objective, add or edit
  _showModal(objective, title, button) {
    this._addModal.show();
    this._currentState = objective;
    document.getElementById('modalTitle').innerHTML = title;
    document.getElementById('addEditConfirmButton').innerHTML = button;
  }

  _showAssignModal(objective, title, button) {
    this._assignModal.show();
    this._currentState = objective;
    document.getElementById('assignModalTitle').innerHTML = title;
    document.getElementById('assignButton').innerHTML = button;
  }

  _showNeedInfoModal(objective, title, button) {
    this._needInfoModal.show();
    this._currentState = objective;
    document.getElementById('needInfoModalTitle').innerHTML = title;
    document.getElementById('needInfoButton').innerHTML = button;
  }

  // Filling the modal form data
  _setForm() {
    const data = this._rowToEdit.data();
    document.querySelector('#addModal input[name=Name]').value = data.Name;
    document.querySelector('#addModal input[name=Sales]').value = data.Sales;
    document.querySelector('#addModal input[name=Stock]').value = data.Stock;
    if (document.querySelector('#addModal ' + 'input[name=Category][value="' + data.Category + '"]')) {
      document.querySelector('#addModal ' + 'input[name=Category][value="' + data.Category + '"]').checked = true;
    }
    if (document.querySelector('#addModal ' + 'input[name=Tag][value="' + data.Tag + '"]')) {
      document.querySelector('#addModal ' + 'input[name=Tag][value="' + data.Tag + '"]').checked = true;
    }
  }

  // Getting form values from the fields to pass to datatable
  _getFormData() {
    const data = {};
    data.Name = document.querySelector('#addModal input[name=Name]').value;
    data.Sales = document.querySelector('#addModal input[name=Sales]').value;
    data.Stock = document.querySelector('#addModal input[name=Stock]').value;
    data.Category = document.querySelector('#addModal input[name=Category]:checked')
      ? document.querySelector('#addModal input[name=Category]:checked').value || ''
      : '';
    data.Tag = document.querySelector('#addModal input[name=Tag]:checked')
      ? document.querySelector('#addModal input[name=Tag]:checked').value || ''
      : '';
    data.Check = '';
    return data;
  }

  // Clearing modal form
  _clearModalForm() {
    document.querySelector('#addModal form').reset();
  }

  // Clearing modal form
  _clearAssignModalForm() {
    document.querySelector('#assignModal form').reset();
  }

  // Clearing modal form
  _clearNeedInfoModalForm() {
    document.querySelector('#needInfoModal form').reset();
  }

  // Update tag from top side dropdown
  _updateTag(tag) {
    const selected = this._datatableExtend.getSelectedRows();
    const _this = this;
    selected.every(function (rowIdx, tableLoop, rowLoop) {
      const data = this.data();
      data.Tag = tag;
      _this._datatable.row(this).data(data).draw();
    });
    this._datatableExtend.unCheckAllRows();
    this._datatableExtend.controlCheckAll();
  }

  // Single item select callback from DatatableExtend
  _onSingleSelect() {
    let SelectedRow = this._datatableExtend.getSelectedRows();
    // this._onEditRowClick(this._datatable.row(selected[0][0]));
    let thisRow = this._datatable.row(SelectedRow[0][0]).data();
    document.querySelectorAll('.review-datatable').forEach((el) => el.classList.remove('disabled'));
    document.querySelectorAll('.edit-datatable').forEach((el) => el.classList.remove('disabled'));
    document.querySelectorAll('.edit-template-datatable').forEach((el) => el.classList.remove('disabled'));
    document.querySelectorAll('.admin-datatable').forEach((el) => el.classList.remove('disabled'));

    document.querySelectorAll('.delete-datatable').forEach((el) => el.classList.remove('disabled'));
    if (thisRow.status != undefined) {
      document.querySelectorAll('.edit-datatable').forEach((el) => el.classList.add('disabled'));
      if (thisRow.amendments.length > 0)
        for (let i = 0; thisRow.amendments; i++) {
          // console.log(thisRow)
          // console.log(thisRow.amendments[i].status)
          if (thisRow.amendments[i] != undefined && thisRow.amendments[i].status == 1 && thisRow.status == "returned")
            document.querySelectorAll('.edit-datatable').forEach((el) => el.classList.remove('disabled'));
          else
            break
        }
      if (thisRow.status != "pending")
        document.querySelectorAll('.delete-datatable').forEach((el) => el.classList.add('disabled'));
    }

    if (thisRow.amendments != undefined) {
      if (thisRow.amendments.length > 0)
        for (let i = 0; thisRow.amendments; i++) {
          if (thisRow.amendments[i] != undefined && thisRow.amendments[i].is_secret == 1 && thisRow.amendments[i].approve_secret == 0 && thisRow.form.status == "returned")
            document.querySelectorAll('.approve-secret-datatable').forEach((el) => el.classList.remove('disabled'));
          else
            break
        }
    }

    if (thisRow.form != undefined) {
      // console.log(thisRow.amendments)
      if (thisRow.form.status == "approved")
        document.querySelectorAll('.close-request-datatable').forEach((el) => el.classList.remove('disabled'));
      if (thisRow.form.status == "processing") {
        if (thisRow.amendments.length === 0) {
          document.querySelectorAll('.need-info-datatable').forEach((el) => el.classList.remove('disabled'));
        } else {
          if (thisRow.amendments[0].status != 1)
            document.querySelectorAll('.need-info-datatable').forEach((el) => el.classList.remove('disabled'));
        }
      }
    }
  }

  // Multiple item select callback from DatatableExtend
  _onMultipleSelect() {
    document.querySelectorAll('.edit-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.edit-template-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.admin-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.review-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.close-request-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.approve-secret-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.need-info-datatable').forEach((el) => el.classList.add('disabled'));
    let SelectedRow = this._datatableExtend.getSelectedRows();
    SelectedRow[0].forEach((entry) => {
      let thisRow = this._datatable.row(entry).data();
      if (thisRow.status != "pending")
        document.querySelectorAll('.delete-datatable').forEach((el) => el.classList.add('disabled'));
    });
  }

  // One or more item select callback from DatatableExtend
  _onAnySelect() {
    let SelectedRow = this._datatableExtend.getSelectedRows();
    SelectedRow[0].forEach((entry) => {
      let thisRow = this._datatable.row(entry).data();
      if (thisRow.deleted_at) {
        document.querySelectorAll('.restore-datatable').forEach((el) => el.classList.remove('disabled'));
      }
      if (thisRow.assigned || thisRow.status == "rejected") {
        document.querySelectorAll('.delete-assigned-datatable').forEach((el) => el.classList.remove('disabled'));
      }
      // if (thisRow.status == "pending") {
      document.querySelectorAll('.assign-datatable').forEach((el) => el.classList.remove('disabled'));
      // }
    });
    document.querySelectorAll('.delete-datatable').forEach((el) => el.classList.remove('disabled'));
    document.querySelectorAll('.tag-datatable').forEach((el) => el.classList.remove('disabled'));
    document.querySelectorAll('.change-password-datatable').forEach((el) => el.classList.remove('disabled'));
    document.querySelectorAll('.message-datatable').forEach((el) => el.classList.remove('disabled'));

  }

  // Deselect callback from DatatableExtend
  _onNoneSelect() {
    document.querySelectorAll('.delete-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.delete-assigned-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.restore-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.change-password-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.tag-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.assign-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.close-request-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.approve-secret-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.need-info-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.review-datatable').forEach((el) => el.classList.add('disabled'));
    document.querySelectorAll('.message-datatable').forEach((el) => el.classList.add('disabled'));
  }
}
