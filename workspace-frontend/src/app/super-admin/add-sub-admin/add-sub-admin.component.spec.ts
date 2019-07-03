import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddSubAdminComponent } from './add-sub-admin.component';

describe('AddSubAdminComponent', () => {
  let component: AddSubAdminComponent;
  let fixture: ComponentFixture<AddSubAdminComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddSubAdminComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddSubAdminComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
