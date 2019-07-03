import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PageBreakSubmitComponent } from './page-break-submit.component';

describe('PageBreakSubmitComponent', () => {
  let component: PageBreakSubmitComponent;
  let fixture: ComponentFixture<PageBreakSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PageBreakSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PageBreakSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
