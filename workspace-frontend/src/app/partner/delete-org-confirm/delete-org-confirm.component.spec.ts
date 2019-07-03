import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DeleteOrgConfirmComponent } from './delete-org-confirm.component';

describe('DeleteOrgConfirmComponent', () => {
  let component: DeleteOrgConfirmComponent;
  let fixture: ComponentFixture<DeleteOrgConfirmComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DeleteOrgConfirmComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DeleteOrgConfirmComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
