import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NewLeaveTypeComponent } from './new-leave-type.component';

describe('NewLeaveTypeComponent', () => {
  let component: NewLeaveTypeComponent;
  let fixture: ComponentFixture<NewLeaveTypeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NewLeaveTypeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NewLeaveTypeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
