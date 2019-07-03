import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EditDeptComponent } from './edit-dept.component';

describe('EditDeptComponent', () => {
  let component: EditDeptComponent;
  let fixture: ComponentFixture<EditDeptComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EditDeptComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EditDeptComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
