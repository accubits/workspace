import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { LeaveTableComponent } from './leave-table.component';

describe('LeaveTableComponent', () => {
  let component: LeaveTableComponent;
  let fixture: ComponentFixture<LeaveTableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LeaveTableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LeaveTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
