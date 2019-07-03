import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ExpiredTableComponent } from './expired-table.component';

describe('ExpiredTableComponent', () => {
  let component: ExpiredTableComponent;
  let fixture: ComponentFixture<ExpiredTableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ExpiredTableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ExpiredTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
