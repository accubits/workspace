import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminTableHeadComponent } from './admin-table-head.component';

describe('AdminTableHeadComponent', () => {
  let component: AdminTableHeadComponent;
  let fixture: ComponentFixture<AdminTableHeadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AdminTableHeadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AdminTableHeadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
