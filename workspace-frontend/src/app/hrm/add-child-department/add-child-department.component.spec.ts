import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddChildDepartmentComponent } from './add-child-department.component';

describe('AddChildDepartmentComponent', () => {
  let component: AddChildDepartmentComponent;
  let fixture: ComponentFixture<AddChildDepartmentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddChildDepartmentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddChildDepartmentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
