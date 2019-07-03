import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ExpiredTableHeadComponent } from './expired-table-head.component';

describe('ExpiredTableHeadComponent', () => {
  let component: ExpiredTableHeadComponent;
  let fixture: ComponentFixture<ExpiredTableHeadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ExpiredTableHeadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ExpiredTableHeadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
