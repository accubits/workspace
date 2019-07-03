import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ActiveDetailPopComponent } from './active-detail-pop.component';

describe('ActiveDetailPopComponent', () => {
  let component: ActiveDetailPopComponent;
  let fixture: ComponentFixture<ActiveDetailPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ActiveDetailPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ActiveDetailPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
