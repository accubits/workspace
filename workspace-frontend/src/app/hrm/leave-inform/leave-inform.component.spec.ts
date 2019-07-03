import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LeaveInformComponent } from './leave-inform.component';

describe('LeaveInformComponent', () => {
  let component: LeaveInformComponent;
  let fixture: ComponentFixture<LeaveInformComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LeaveInformComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LeaveInformComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
